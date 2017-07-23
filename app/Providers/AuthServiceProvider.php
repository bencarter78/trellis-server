<?php

namespace App\Providers;

use App\Team;
use App\Stream;
use App\Project;
use App\Milestone;
use App\Policies\TeamPolicy;
use App\Policies\StreamPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\MilestonePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Milestone::class => MilestonePolicy::class,
        Project::class => ProjectPolicy::class,
        Stream::class => StreamPolicy::class,
        Team::class => TeamPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
