<?php

namespace App;

use Laravel\Nova\Actions\Actionable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
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
        'available',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Get the approver for the project.
     */
    public function approver()
    {
        return $this->belongsTo('App\\User', 'approver_id');
    }

    /**
     * Get the fiscal year for the project.
     */
    public function fiscalYear()
    {
        return $this->belongsTo('App\\FiscalYear');
    }

    /**
     * Get the requisitions for the project.
     */
    public function requisitions()
    {
        return $this->hasMany('App\Requisition');
    }

    /**
     * Get total used amount for the project.
     */
    public function getUsedAttribute()
    {
        return $this->requisitions()
            ->whereNotIn('state', ['draft', 'pending_approval'])
            ->get()->sum('amount');
    }

    /**
     * Get total pending amount for the project.
     */
    public function getPendingAttribute()
    {
        return $this->requisitions()
            ->whereIn('state', ['draft', 'pending_approval'])
            ->get()->sum('amount');
    }

    /**
     * Get the suggested name for the next requisition.
     */
    public function getNextRequisitionNameAttribute()
    {
        $year = $this->fiscalYear->number;
        $base_name = $this->requisition_prefix.'-'.$year.'-PO-';
        $number = 1;

        $all_names = Requisition::where('project_id', $this->id)
            ->withTrashed()
            ->orderBy('id', 'asc')
            ->pluck('name');

        // Find a number not yet in the array
        $name = $base_name.'001';
        while ($all_names->contains($name)) {
            $number++;
            $name = sprintf('%s%03d', $name, $number);
        }

        return $name;
    }
}
