<?php

namespace App\Nova\Actions;

use Notification;
use App\Requisition;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
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
            $approvers = collect([]);
            $project_approver = $requisition->project->approver;
            if ($project_approver != null) {
                $approvers[] = $project_approver;
            }

            foreach ($requisition->lines as $req_line) {
                $account_line = $req_line->account_line;
                $account_line_approver = $account_line->approver;
                if ($account_line_approver != null) {
                    $approvers[] = $accont_line_approver;
                }
            }

            $approvers = $approvers->unique();
            $total_approvers = $total_approvers->concat($approvers);
            // Notification::send(); // FIXME

            if ($approvers->count() > 0) {
                $requisition->state = 'pending_approval';
                $requisition->save();

                \Log::debug('Requisition '.$requisition->name.' needs approval from '.$approvers->pluck('name')->implode(', '));

                // TODO: don't hardcode path
                $path = url(config('nova.path').'/resources/requisitions/'.$requisition->id);
                foreach ($approvers as $approver) {
                    $approver->notify(new ApprovalRequestedNotification($requisition->name, $path, request()->user()->name));
                }
                // Notification::send($approvers, new ApprovalRequestedNotification($requisition->name, $path, request()->user()->name));
            } else {
                \Log::warning('Requisition '.$requisition->name.' does not require any approvals');
                return Action::danger($requisition->name.' does not have any approvers. Please ask the treasurer for approval.'); // FIXME
            }
        }

        $names = $total_approvers->unique()->pluck('name');
        \Log::debug('Requested approvals from '.$names->implode(', '));
        if ($names->count() == 1) {
            $approvers_string = $names[0];
        } else if ($names->count() == 2) {
            $approvers_string = $names[0].' and '.$names[1];
        } else {
            $approvers_string = $names->slice(0, $names->count() - 1)->implode(', ').', and '.$names->last();
        }

        return Action::message('Requested approval from '.$approvers_string.'.');
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
