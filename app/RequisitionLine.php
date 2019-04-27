<?php

namespace App;

use Laravel\Nova\Actions\Actionable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionLine extends Model
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
     * Get the requisition for the line.
     */
    public function requisition()
    {
        return $this->belongsTo('App\\Requisition');
    }

    /**
     * Get the account line for the line.
     */
    public function account_line()
    {
        return $this->belongsTo('App\\AccountLine');
    }

    /**
     * Get the total cost of this line.
     */
    public function getAmountAttribute()
    {
        return $this->cost * $this->quantity;
    }
}
