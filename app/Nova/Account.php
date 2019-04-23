<?php

namespace App\Nova;

use Laravel\Nova\Panel;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;

class Account extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Account';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [ 'name' ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('Name')
                ->sortable(),

            BelongsTo::make('Fiscal Year', 'fiscalYear')
                ->hideFromIndex(),

            Text::make('SGA Bill Number')
                ->hideFromIndex(),

            Text::make('Workday Account Number', 'workday_number')
                ->hideFromIndex(),

            new Panel('Amounts', $this->amountFields()),

            HasMany::make('Lines', 'accountLines', 'App\Nova\AccountLine'),
        ];
    }

    protected function amountFields()
    {
        return [
            Currency::make('Allocated')
                ->exceptOnForms(),

            Currency::make('Used')
                ->exceptOnForms(),

            Currency::make('Collected')
                ->exceptOnForms(),

            Currency::make('Remaining')
                ->exceptOnForms(),

            Currency::make('Overdraw')
                ->exceptOnForms(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
