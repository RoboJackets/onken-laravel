<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Take a list and turn it into sentence form: A and B or A, B, and C
        // Technically this should be in its own provider
        Collection::macro('toSentence', function () {
            if ($this->count() == 0) {
                return '';
            } else if ($this->count() == 1) {
                return $this[0];
            } else if ($this->count() == 2) {
                return $this[0].' and '.$this[1];
            } else {
                return $this->slice(0, $this->count() - 1)->implode(', ').', and '.$this->last();
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
