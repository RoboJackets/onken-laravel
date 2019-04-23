<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;

class Requisition extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Requisition';

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
            Text::make('Name'),

            BelongsTo::make('Vendor'),

            BelongsTo::make('Fiscal Year', 'fiscalYear', 'App\Nova\FiscalYear'),

            Select::make('State')
                ->options([
                    'Draft',
                    'Pending Approval',
                    'Approved',
                    'Ordered',
                    'Partially Shipped',
                    'Fully Shipped',
                    'Partially Received',
                    'Fully Received',
                ]),

            BelongsTo::make('Technical Contact', 'technicalContact', 'App\Nova\User'),

            BelongsTo::make('Finance Contact', 'financeContact', 'App\Nova\User'),

            DateTime::make('Receive By'),

            Text::make('Exception')
                ->nullable(),

            BelongsTo::make('Exception Author', 'exceptionAuthor', 'App\Nova\User')
                ->nullable(),

            Textarea::make('Note')
                ->nullable(),

            HasMany::make('Lines', 'lines', 'App\Nova\RequisitionLine'),
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
