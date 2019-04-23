<?php

namespace App;

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
     * Get the vendor for the tag.
     */
    public function vendor()
    {
        return $this->belongsTo('App\\Vendor');
    }

    public function fiscalYear()
    {
        return $this->belongsTo('App\\FiscalYear');
    }

    public function lines()
    {
        return $this->hasMany('App\\RequisitionLine');
    }

    public function technicalContact()
    {
        return $this->belongsTo('App\\User', 'technical_contact_id');
    }

    public function financeContact()
    {
        return $this->belongsTo('App\\User', 'finance_contact_id');
    }

    public function exceptionAuthor()
    {
        return $this->belongsTo('App\\User', 'exception_author_id');
    }
}
