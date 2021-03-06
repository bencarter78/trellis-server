<?php

namespace Tests\Feature\Api;

use App\Project;
use Tests\TestCase;
use App\Jobs\SendInvitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ProjectInviteControllerTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    /** @test */
    public function a_project_owner_can_send_an_invite_to_an_email()
    {
        $this->expectsJobs(SendInvitation::class);

        $user = $this->authUser();

        $project = factory(Project::class)->create(['owner_id' => $user->id]);

        $this->post("/api/projects/{$project->uid}/invite", ['email' => 'test@test.com'])
             ->assertStatus(200);
    }
}
