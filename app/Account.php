<?php

namespace App;

use Laravel\Nova\Actions\Actionable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
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
     * Get the fiscal year for the account.
     */
    public function fiscalYear()
    {
        return $this->belongsTo('App\FiscalYear');
    }

    /**
     * Get the lines for the account.
     */
    public function accountLines()
    {
        return $this->hasMany('App\AccountLine');
    }

    /**
     * Get total allocated amount for the account.
     */
    public function getAllocatedAttribute()
    {
        return $this->accountLines()->get()->sum('amount');
    }

    /**
     * Get total used amount for the account.
     */
    public function getUsedAttribute()
    {
        return $this->accountLines()->get()->sum('used');
    }

    /**
     * Get total pending amount for the account.
     */
    public function getPendingAttribute()
    {
        return $this->accountLines()->get()->sum('pending');
    }

    /**
     * Get total collected amount for the account.
     */
    public function getCollectedAttribute()
    {
        return $this->accountLines()->get()->sum('collected');
    }

    /**
     * Get total remaining amount for the account.
     */
    public function getRemainingAttribute()
    {
        return $this->accountLines()->get()->reduce(function ($carry, $line) {
            // Don't add negative lines
            return $carry + max($line->remaining, 0);
        });
    }

    /**
     * Get total overdraw amount for the account.
     */
    public function getOverdrawAttribute()
    {
        return $this->accountLines()->get()->sum('overdraw');
    }
}
