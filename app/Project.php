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
}
