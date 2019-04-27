<?php

namespace App;

use Laravel\Nova\Actions\Actionable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class FiscalYear extends Model
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
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Get the accounts for the fiscal year.
     */
    public function accounts()
    {
        return $this->hasMany('App\Account');
    }

    /**
     * Get total allocated amount for the account.
     */
    public function getAllocatedAttribute()
    {
        return $this->accounts()->get()->sum('allocated');
    }

    /**
     * Get total used amount for the account.
     */
    public function getUsedAttribute()
    {
        return $this->accounts()->get()->sum('used');
    }

    /**
     * Get total pending amount for the account.
     */
    public function getPendingAttribute()
    {
        return $this->accounts()->get()->sum('pending');
    }

    /**
     * Get total collected amount for the account.
     */
    public function getCollectedAttribute()
    {
        return $this->accounts()->get()->sum('collected');
    }

    /**
     * Get total remaining amount for the account.
     */
    public function getRemainingAttribute()
    {
        return $this->accounts()->get()->sum('remaining');
    }

    /**
     * Get total overdraw amount for the account.
     */
    public function getOverdrawAttribute()
    {
        return $this->accounts()->get()->sum('overdraw');
    }
}
