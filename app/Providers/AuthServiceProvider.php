<?php

namespace App\Providers;

use App\Task;
use App\Team;
use App\Stream;
use App\Project;
use App\Milestone;
use App\Objective;
use App\Policies\TeamPolicy;
use App\Policies\TaskPolicy;
use App\Policies\StreamPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\MilestonePolicy;
use App\Policies\ObjectivePolicy;
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
        Objective::class => ObjectivePolicy::class,
        Project::class => ProjectPolicy::class,
        Stream::class => StreamPolicy::class,
        Task::class => TaskPolicy::class,
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
