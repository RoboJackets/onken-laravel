<?php

namespace App;

use Laravel\Nova\Actions\Actionable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountLine extends Model
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
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Get the account for the account line.
     */
    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    /**
     * Get the approver for this line.
     */
    public function approver()
    {
        return $this->belongsTo('App\User', 'approver_id', 'id');
    }

    /**
     * Get the requisition lines using this acconut line.
     */
    public function requisitionLines()
    {
        return $this->hasMany('App\RequisitionLine', 'account_line_id', 'id');
    }

    /**
     * Get total used amount for the line.
     */
    public function getUsedAttribute()
    {
        return $this->requisitionLines()
            ->whereDoesntHave('requisition', function ($query) {
                $query->whereIn('state', ['Draft', 'Pending Approval']);
            })->get()
            ->sum('amount');
    }

    /**
     * Get total pending amount for the line.
     */
    public function getPendingAttribute()
    {
        return $this->requisitionLines()
            ->whereHas('requisition', function ($query) {
                $query->whereIn('state', ['draft', 'pending_approval']);
            })->get()
            ->sum('amount');
    }

    /**
     * Get total collected amount for the line.
     */
    public function getCollectedAttribute()
    {
        // FIXME
        return 0;
    }

    /**
     * Get total remaining amount for the line.
     */
    public function getRemainingAttribute()
    {
        return $this->amount - $this->used + $this->collected;
    }

    /**
     * Get total overdraw amount for the line.
     */
    public function getOverdrawAttribute()
    {
        // If remaining is negative, return the amount but positive. Otherwise return 0.
        return -min($this->remaining, 0);
    }
}
