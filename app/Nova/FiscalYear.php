<?php

namespace App\Nova;

use Laravel\Nova\Panel;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;

class FiscalYear extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\FiscalYear';

    /**
     * Get the displayble label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Fiscal Years';
    }

    /**
     * Get the displayble singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Fiscal Year';
    }

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
    public static $search = ['number'];

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
                ->exceptOnForms()
                ->sortable(),

            Text::make('Number')
                ->rules('integer', 'required', 'lt:2080', 'gte:1980')
                ->hideFromIndex(),

            Boolean::make('Active')
                ->sortable(),

            Date::make('Start Date')
                ->format('MM/DD/YYYY')
                ->rules('required', 'date')
                ->hideFromIndex(),

            Date::make('End Date')
                ->format('MM/DD/YYYY')
                ->rules('required', 'date')
                ->hideFromIndex(),

            new Panel('Amounts', $this->amountFields()),

            HasMany::make('Accounts'),
        ];
    }

    protected function amountFields()
    {
        return [
            Currency::make('Allocated')
                ->sortable()
                ->exceptOnForms(),

            Currency::make('Used')
                ->sortable()
                ->exceptOnForms(),

            Currency::make('Pending')
                ->onlyOnDetail(),

            Currency::make('Collected')
                ->sortable()
                ->exceptOnForms(),

            Currency::make('Remaining')
                ->sortable()
                ->exceptOnForms(),

            Currency::make('Overdraw')
                ->sortable()
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
