<?php

namespace Tests\Feature\Api;

use App\Project;
use App\Objective;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TaskControllerTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test */
    public function it_returns_all_objectives_for_a_project()
    {
        $project = factory(Project::class)->create();

        $project->members()->attach($this->authUser()->id);

        $objectives = factory(Objective::class, 2)->create(['project_id' => $project->id]);

        $this->get("/api/projects/{$project->uid}/objectives")
             ->assertStatus(200)
             ->assertJson(['data' => ['objectives' => $objectives->toArray()]]);
    }

    /** @test */
    public function it_stores_a_new_objective_for_a_project()
    {
        $user = $this->authUser();

        $project = factory(Project::class)->create(['owner_id' => $user->id]);

        $objective = factory(Objective::class)->make(['project_id' => $project->id]);

        $this->post("/api/projects/{$project->uid}/objectives", [
            'project_id' => $project->uid,
            'name' => $objective->name,
            'due_on' => $objective->due_on->format('d/m/Y'),
        ])
             ->assertStatus(200)
             ->assertJson([
                 'data' => [
                     'objective' => [
                         'name' => $objective->name,
                         'due_on' => $objective->due_on,
                     ],
                 ],
             ]);
    }

    /** @test */
    public function it_aborts_when_a_user_is_not_the_project_owner()
    {
        $user = $this->authUser();

        $objective = factory(Objective::class)->make();

        $this->post("/api/projects/{$objective->project->uid}/objectives", [
            'project_id' => $objective->project_uid,
            'name' => $objective->name,
        ])
             ->assertStatus(403);
    }

    /** @test */
    public function it_removes_an_objective_from_a_project()
    {
        $user = $this->authUser();

        $project = factory(Project::class)->create(['owner_id' => $user->id]);

        $objective = factory(Objective::class)->create(['project_id' => $project->id]);

        $this->delete("/api/projects/{$project->uid}/objectives/{$objective->uid}")
             ->assertStatus(200);
    }
}
