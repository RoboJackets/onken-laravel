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

class Approve extends Action
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
            return Action::danger('You can only approve one requisition at one time.');
        }

        $requisition = $models[0];
        if ($requisition->amount == 0) {
            $this->markAsFailed($requisition);
            return Action::danger('Requisitions must have a non-zero cost to be approved.');
        } else if ($requisition->state != 'pending_approval') {
            $this->markAsFailed($requisition);
            return Action::danger('You can only approve requisitions that are pending approval.');
        }

        $approval = new Approval;
        $approval->user()->associate($request->user());
        $approval->requisition()->associate($requisition);
        $approval->save();

        if ($request->user()->can('create-approvals') || $requisition->approvers_pending->count() == 0) {
            $requisition->state = 'approved';
            $requisition->save();

            \Log::debug('Requisition '.$requisition->name.' approved.');
            (new AdminNotifiable)->notify(new RequisitionApprovedNotification($requisition));
        }

        return Action::message('Requisition approved.');
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
}
