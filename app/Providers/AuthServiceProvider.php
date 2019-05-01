<?php

namespace App\Providers;

use App\User;
use App\Vendor;
use App\Account;
use App\Project;
use App\Approval;
use App\VendorTag;
use App\FiscalYear;
use App\VendorNote;
use App\AccountLine;
use App\Requisition;
use App\VendorOrder;
use App\RequisitionLine;
use App\Policies\UserPolicy;
use App\Policies\VendorPolicy;
use App\Policies\AccountPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\ApprovalPolicy;
use App\Policies\VendorTagPolicy;
use App\Policies\FiscalYearPolicy;
use App\Policies\VendorNotePolicy;
use App\Policies\AccountLinePolicy;
use App\Policies\RequisitionPolicy;
use App\Policies\VendorOrderPolicy;
use Illuminate\Support\Facades\Gate;
use App\Policies\RequisitionLinePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Vendor::class => VendorPolicy::class,
        Account::class => AccountPolicy::class,
        Project::class => ProjectPolicy::class,
        Approval::class => ApprovalPolicy::class,
        VendorTag::class => VendorTagPolicy::class,
        FiscalYear::class => FiscalYearPolicy::class,
        VendorNote::class => VendorNotePolicy::class,
        AccountLine::class => AccountLinePolicy::class,
        Requisition::class => RequisitionPolicy::class,
        VendorOrder::class => VendorOrderPolicy::class,
        RequisitionLine::class => RequisitionLinePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
