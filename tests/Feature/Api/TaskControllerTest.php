<?php

namespace Tests\Feature\Api;

use App\Task;
use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TaskControllerTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test
    @small */
    public function it_returns_all_project_tasks_for_a_project_member()
    {
        $project = factory(Project::class)->create();

        $project->members()->attach($this->authUser()->id);

        $tasks = factory(Task::class, 2)->create([
            'owner_id' => $project->id,
            'owner_type' => Project::class,
        ]);

        $this->get("/api/tasks?resource=project&uid={$project->uid}")
             ->assertStatus(200)
             ->assertJson(['data' => ['tasks' => $tasks->toArray()]]);
    }

    /** @test */
    public function it_stores_a_new_task_for_a_project()
    {
        $user = $this->authUser();

        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        $task = factory(Task::class)->make(['project_id' => $project->id]);


        $this->post("/api/projects/{$project->uid}/tasks", [
            'project_id' => $project->uid,
            'name' => $task->name,
            'due_on' => $task->due_on->format('d/m/Y'),
        ])
             ->assertStatus(200)
             ->assertJson([
                 'data' => [
                     'task' => [
                         'name' => $task->name,
                         'due_on' => $task->due_on,
                     ],
                 ],
             ]);
    }

    /** @test */
    public function it_aborts_when_a_user_is_not_the_project_owner()
    {
        $user = $this->authUser();
        $task = factory(Task::class)->make();


        $this->post("/api/projects/{$task->project->uid}/tasks", [
            'project_id' => $task->project_uid,
            'name' => $task->name,
        ])
             ->assertStatus(403);
    }

    /** @test */
    public function it_removes_an_task_from_a_project()
    {
        $user = $this->authUser();

        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        $task = factory(Task::class)->create(['project_id' => $project->id]);


        $this->delete("/api/projects/{$project->uid}/tasks/{$task->uid}")
             ->assertStatus(200);
    }
}
