<?php

namespace App\Nova\Fields;

use Laravel\Nova\Fields\Currency as NovaCurrency;

class Currency extends NovaCurrency
{
    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|null  $attribute
     * @param  mixed|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->format('$%.2n')->displayUsing(function ($value) {
            if (is_null($value)) {
                return '—';
            } else if ($value == 0) {
                return '—';
            } else {
                return money_format($this->format ?? '%i', $value);
            }
        });
    }

    /**
     * Set the number of digits after the decimal point.
     *
     * @param  number  $digits
     * @return App\Nova\Fields\Currency
     */
    public function precision($digits)
    {
        return $this->format('$%.'.$digits.'n');
    }
}
