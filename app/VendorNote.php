<?php

namespace App;

use Laravel\Nova\Actions\Actionable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorNote extends Model
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
        'user_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Get the vendor for the note.
     */
    public function vendor()
    {
        return $this->belongsTo('App\\Vendor');
    }

    /**
     * Get the author of the note.
     */
    public function user()
    {
        return $this->belongsTo('App\\User');
    }

    /**
     * Get the children notes of the note.
     */
    public function children()
    {
        return $this->hasMany('App\\VendorNote', 'parent_id');
    }

    /**
     * Get the parent notes of the note.
     */
    public function parent()
    {
        return $this->belongsTo('App\\VendorNote', 'parent_id');
    }
}
