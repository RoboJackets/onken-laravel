<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Textarea;

class Vendor extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Vendor';

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
    public static $search = [
        'name', 'website',
    ];

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

            Country::make('Nationality')
                ->hideFromIndex(),

            Textarea::make('Billing Address')
                ->hideFromIndex(),

            Text::make('GT Vendor ID')
                ->rules('integer')
                ->nullable()
                ->hideFromIndex(),

            Text::make('Status')
                ->nullable()
                ->hideFromIndex(),

            Textarea::make('Sales Contact')
                ->nullable()
                ->hideFromIndex(),

            Text::make('Customer')
                ->nullable()
                ->hideFromIndex(),

            Boolean::make('Web Account Exists'),
            
            Text::make('Website')
                ->nullable(),

            Text::make('Part URL Schema')
                ->nullable()
                ->hideFromIndex(),

            Boolean::make('Shipping Quote Required')
                ->hideFromIndex(),

            Boolean::make('Tax Exempt')
                ->hideFromIndex(),

            Textarea::make('Requisition Guidance')
                ->nullable()
                ->hideFromIndex(),

            HasMany::make('Tags', 'tags', 'App\Nova\VendorTag'),

            HasMany::make('Notes', 'notes', 'App\Nova\VendorNote'),
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
