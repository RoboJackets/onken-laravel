<?php

namespace App;

use App\Approval;
use Laravel\Nova\Actions\Actionable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requisition extends Model
{
    use SoftDeletes;
    use Actionable;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'receive_by',
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'state',
        'exception_author_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Get the vendor for the requisition.
     */
    public function vendor()
    {
        return $this->belongsTo('App\\Vendor');
    }

    /**
     * Get the lines for the requisition.
     */
    public function lines()
    {
        return $this->hasMany('App\\RequisitionLine');
    }

    /**
     * Get the technical contact for the requisition.
     */
    public function technicalContact()
    {
        return $this->belongsTo('App\\User', 'technical_contact_id');
    }

    /**
     * Get the finance contact for the requisition.
     */
    public function financeContact()
    {
        return $this->belongsTo('App\\User', 'finance_contact_id');
    }

    /**
     * Get the author of the exception for the requisition.
     */
    public function exceptionAuthor()
    {
        return $this->belongsTo('App\\User', 'exception_author_id');
    }

    /**
     * Get the project for the requisition.
     */
    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    /**
     * Get the approvals for the requisition.
     */
    public function approvals()
    {
        return $this->hasMany('App\Approval');
    }

    /**
     * Get the vendor order for the requisition.
     */
    public function vendorOrder()
    {
        return $this->belongsTo('App\VendorOrder');
    }

    /**
     * Get the total cost of this requisition.
     */
    public function getAmountAttribute()
    {
        return $this->lines()->get()->reduce(function($carry, $line) {
            return $carry + $line->cost * $line->quantity;
        }, 0);
    }

    /**
     * Get all users whose approval is required for this requisition.
     */
    public function getApproversAttribute()
    {
        $project_approver = $this->project->approver;
        $line_approvers = $this->lines->pluck('approver');
        if ($line_approvers != null) {
            return $line_approvers->concat([$project_approver])
                ->unique()
                ->filter(function ($item) {
                    return $item != null;
                });
        } else {
            return $project_approver ? collect([$project_approver]) : collect();
        }
    }

    /**
     * Get all users whose approval is required and not present for this requisition.
     */
    public function getApproversPendingAttribute()
    {
        if ($this->state != 'pending_approval') {
            return collect();
        }
        return $this->approvers->diff(Approval::where('requisition_id', $this->id)->with('user')->get()->pluck('user'));
    }

    /**
     * Get a list of (overdraw, account line) arrays of overdraw amounts that will be caused by this requisition. Only applicable to draft requisitions.
     */
    public function getOverdrawAttribute()
    {
        if ($this->state == 'draft' || $this->state == 'pending_approval') {
            return $this->lines()->with('account_line')->get()
                ->groupBy('account_line_id')
                ->map(function($req_lines) {
                    // $lines is an array of req lines with the same account line
                    $account_line = $req_lines[0]->account_line;
                    $cost = $req_lines->sum('amount');
                    $overdraw = $cost - $account_line->remaining;

                    return [$overdraw, $account_line];
                })->values()->filter(function ($tuple) {
                    return $tuple[0] > 0;
                });
        } else {
            return collect();
        }
    }
}
