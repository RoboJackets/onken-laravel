<?php

namespace App\Nova\Actions;

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

class RequestApproval extends Action
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
        if (!$models->every('state', 'draft')) {
            foreach ($models as $requisition) {
                $this->markAsFailed($requisition);
            }
            return Action::danger('You can only request approval for draft requisitions.');
        } else if ($models->contains('amount', 0)) {
            foreach ($models as $requisition) {
                $this->markAsFailed($requisition);
            }
            return Action::danger('Requisitions must have a non-zero cost to be approved.');
        }

        $total_approvers = collect();
        foreach ($models as $requisition) {
            $approvers = $requisition->approvers;
            $approver_names = $approvers ? $approvers->pluck('name') : [];
            $total_approvers = $total_approvers->concat($approver_names);

            // TODO: don't hardcode path
            $path = url(config('nova.path').'/resources/requisitions/'.$requisition->id);
            $notification = new ApprovalRequestedNotification($requisition->name, $path, request()->user()->name);

            $requisition->state = 'pending_approval';
            $requisition->save();

            if ($approvers->count() > 0) {
                \Log::debug('Requisition '.$requisition->name.' needs approval from '.$approver_names->toSentence());

                // Notification::send($approvers, etc.) did not work for some reason
                foreach ($approvers as $approver) {
                    $approver->notify($notification);
                }
            } else {
                \Log::debug('Requisition '.$requisition->name.' needs approval from the treasurer');
                $total_approvers[] = 'the treasurer';
                (new AdminNotifiable)->notify($notification);
            }
        }

        $total_approvers = $total_approvers->unique();
        // \Log::debug('Requested approvals from '.$names->implode(', '));
        return Action::message('Requested approval from '.$total_approvers->toSentence().'.');
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
    public $onlyOnDetail = false;
}
