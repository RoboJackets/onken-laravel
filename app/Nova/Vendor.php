<?php

namespace App\Nova;

use Laravel\Nova\Panel;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
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
    public static $model = 'App\Vendor';

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
    public static $search = ['name', 'website'];

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

            Select::make('Status')
                ->options([
                    'unapproved' => 'Unapproved',
                    'approved' => 'Approved',
                    'preferred' => 'Preferred',
                    'discouraged' => 'Discouraged',
                    'unavailable' => 'Unavailable',
                ])->displayUsingLabels()
                ->readonly(function ($request) {
                    return !$request->user()->can('update-vendors');
                })->hideFromIndex(),

            Text::make('Website')
                ->sortable()
                ->nullable(),

            Country::make('Nationality')
                ->hideFromIndex(),

            Textarea::make('Requisition Guidance')
                ->nullable()
                ->help('Notes for people placing requisitions with this vendor')
                ->hideFromIndex(),

            new Panel('Detailed Information', $this->detailedFields()),

            HasMany::make('Tags', 'tags', 'App\Nova\VendorTag'),

            HasMany::make('Notes', 'notes', 'App\Nova\VendorNote'),

            new Panel('Metadata', $this->metaFields()),
        ];
    }

    protected function detailedFields()
    {
        return [
            Textarea::make('Billing Address')
                ->nullable()
                ->hideFromIndex()
                ->canSee(function ($request) {
                    return $request->user()->can('read-vendors_detailed');
                }),

            Boolean::make('Web Account Exists')
                ->sortable()
                ->canSee(function ($request) {
                    return $request->user()->can('read-vendors_detailed');
                }),

            Boolean::make('Shipping Quote Required')
                ->hideFromIndex()
                ->canSee(function ($request) {
                    return $request->user()->can('read-vendors_detailed');
                }),

            Boolean::make('Tax Exempt')
                ->hideFromIndex()
                ->canSee(function ($request) {
                    return $request->user()->can('read-vendors_detailed');
                }),

            Text::make('GT Vendor ID')
                ->rules('integer', 'nullable')
                ->nullable()
                ->hideFromIndex()
                ->canSee(function ($request) {
                    return $request->user()->can('read-vendors_detailed');
                }),

            Textarea::make('Sales Contact')
                ->nullable()
                ->hideFromIndex()
                ->canSee(function ($request) {
                    return $request->user()->can('read-vendors_detailed');
                }),

            /*Text::make('Customer')
                ->nullable()
                ->hideFromIndex(),*/ // TODO: fix this

            Text::make('Part URL Schema')
                ->nullable()
                ->hideFromIndex()
                ->help('Put {sku} in the URL where the part SKU should go.')
                ->canSee(function ($request) {
                    return $request->user()->can('read-vendors_detailed');
                }),
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
