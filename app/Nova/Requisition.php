<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Nova\Fields\Currency;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasMany;
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
    public static $model = 'App\Requisition';

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
    public static $search = ['name'];

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

            BelongsTo::make('Project')
                ->sortable()
                ->hideFromIndex(),

            BelongsTo::make('Vendor')
                ->sortable(),

            Currency::make('Total Cost', 'amount')
                ->sortable()
                ->exceptOnForms(),

            Select::make('State')
                ->sortable()
                ->options([
                    'draft' => 'Draft',
                    'pending_approval' => 'Pending Approval',
                    'approved' => 'Approved',
                    'ordered' => 'Ordered',
                    'partially_shipped' => 'Partially Shipped',
                    'fully_shipped' => 'Fully Shipped',
                    'partially_received' => 'Partially Received',
                    'fully_received' => 'Fully Received',
                ])->displayUsingLabels(),

            BelongsTo::make('Technical Contact', 'technicalContact', 'App\Nova\User')
                ->hideFromIndex(),

            BelongsTo::make('Finance Contact', 'financeContact', 'App\Nova\User')
                ->hideFromIndex(),

            DateTime::make('Receive By')
                ->hideFromIndex(),

            Text::make('Exception')
                ->hideFromIndex()
                ->nullable(),

            BelongsTo::make('Exception Author', 'exceptionAuthor', 'App\Nova\User')
                ->hideFromIndex()
                ->nullable(),

            Textarea::make('Note')
                ->hideFromIndex()
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
