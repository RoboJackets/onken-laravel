<?php

namespace App\Nova\Actions;

use App\Approval;
use Notification;
use App\Requisition;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use App\Notifiable\AdminNotifiable;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\ApprovalRequestedNotification;
use App\Notifications\RequisitionApprovedNotification;

class Disapprove extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if ($models->count() != 1) {
            foreach ($models as $requisition) {
                $this->markAsFailed($requisition);
            }
            return Action::danger('You can only return one requisition to draft at one time.');
        }

        $requisition = $models[0];

        if (!($requisition->state == 'pending_approval' || ($requisition->state == 'approved' && request()->user()->can('delete-approvals')))) {
            $this->markAsFailed($requisition);
            // TODO: check this
            if (request()->user()->can('delete-approvals')) {
                return Action::danger('You can only disapprove requisitions that are pending approval or approved.');
            } else {
                return Action::danger('You can only disapprove requisitions that are pending approval.');
            }
        }

        Approval::where('requisition_id', $requisition->id)->delete();

        $requisition->state = 'draft';
        $requisition->save();

        \Log::debug('Requisition '.$requisition->name.' returned to draft.');

        return Action::message('Requisition returned to draft.');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }

    /**
     * Indicates if this action is only available on the resource detail view.
     *
     * @var bool
     */
    public $onlyOnDetail = true;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Return to Draft / Disapprove';
}
