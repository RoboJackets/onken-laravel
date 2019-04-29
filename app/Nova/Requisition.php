<?php

namespace App\Nova;

use App\Project;
use Laravel\Nova\Panel;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Nova\Actions\Approve;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use App\Nova\Fields\Currency;
use Laravel\Nova\Fields\Select;
use App\Nova\Actions\Disapprove;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use app\Nova\Actions\RequestApproval;

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
                ->readonly(true)
                ->help('This field is auto-generated.')
                ->fillUsing(function ($request, $model, $attribute) {
                    if ($model->{$attribute} != null) return;

                    // Get the project for this requisition
                    $project = Project::find($request->project);
                    $model->{$attribute} = $project->next_requisition_name;
                })->sortable(),

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
                ->hideWhenCreating()
                ->help('To mark this requisition as pending approval or approved, press save and use their corresponding option in the action dropdown.')
                ->readonly(true)
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
                ->help('Who should be contacted for issues with particular items?')
                ->hideFromIndex(),

            BelongsTo::make('Finance Contact', 'financeContact', 'App\Nova\User')
                ->help('Who should be contacted for issues with funding?')
                ->hideFromIndex(),

            Date::make('Receive By')
                ->help('For exceptional cases, the date a requisition is needed by. If there is no pressing deadline this should be blank.')
                ->hideFromIndex(),

                /* TODO: figure out a better way to handle this
            Text::make('Exception')
                ->hideFromIndex()
                ->nullable(),

            BelongsTo::make('Exception Author', 'exceptionAuthor', 'App\Nova\User')
                ->hideFromIndex()
                ->nullable(),
                 */

            Text::make('Note')
                ->hideFromIndex()
                ->nullable(),

            HasMany::make('Lines', 'lines', 'App\Nova\RequisitionLine'),

            new Panel('Metadata', $this->metaFields()),
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
        return [
            (new RequestApproval)->canSee(function ($request) {
                return $request->user()->can('update-requisitions');
            })->canRun(function ($request, $requisition) {
                // Allow even on requisitions not on draft state to allow nicer error messages
                return $request->user()->can('update-requisitions');
            }),
            (new Approve)->canSee(function ($request) {
                return $request->user()->can('update-requisitions') && $request->user()->is_approver;
            })->canRun(function ($request, $requisition) {
                return $request->user()->can('update-requisitions') && $request->user()->is_approver;
            }),
            (new Disapprove)->canSee(function ($request) {
                return $request->user()->can('update-requisitions');
            })->canRun(function ($request, $requisition) {
                return $request->user()->can('update-requisitions');
            }),
        ];
    }
}
