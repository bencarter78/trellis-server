<?php

namespace Tests\Feature\Api;

use App\Project;
use App\Milestone;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MilestoneControllerTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test */
    public function it_stores_a_new_milestone_for_a_project()
    {
        $user = $this->authUser();
        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        $milestone = factory(Milestone::class)->make(['project_id' => $project->id]);

        $this->post("/api/projects/{$project->uid}/milestones", [
            'name' => $milestone->name,
            'description' => $milestone->description,
            'due_on' => $milestone->due_on,
        ])
             ->assertStatus(200)
             ->assertJson(['data' => ['milestone' => ['name' => $milestone->name]]]);
    }

    /** @test */
    public function it_aborts_when_a_user_is_not_the_project_owner()
    {
        $this->authUser();
        $milestone = factory(Milestone::class)->make();

        $this->post("/api/projects/{$milestone->project->uid}/milestones", [
            'name' => $milestone->name,
            'description' => $milestone->description,
            'due_on' => $milestone->due_on,
        ])->assertStatus(403);
    }

    /** @test */
    public function it_returns_a_given_milestone_for_a_project()
    {
        $user = $this->authUser();
        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        $milestone = factory(Milestone::class)->create(['project_id' => $project->id]);

        $this->get("/api/projects/{$project->uid}/milestones/{$milestone->uid}")
             ->assertStatus(200)
             ->assertJson(['data' => ['milestone' => $milestone->toArray()]]);
    }

    /** @test */
    public function it_returns_a_given_milestone_for_a_project_member()
    {
        $user = $this->authUser();
        $project = factory(Project::class)->create();
        $project->members()->attach($user->id);
        $milestone = factory(Milestone::class)->create(['project_id' => $project->id]);

        $this->get("/api/projects/{$project->uid}/milestones/{$milestone->uid}")
             ->assertStatus(200)
             ->assertJson(['data' => ['milestone' => $milestone->toArray()]]);
    }

    /** @test */
    public function it_removes_an_milestone_from_a_project_by_a_project_owner()
    {
        $user = $this->authUser();
        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        $milestone = factory(Milestone::class)->create(['project_id' => $project->id]);

        $this->delete("/api/projects/{$project->uid}/milestones/{$milestone->uid}")
             ->assertStatus(200);
    }
}
