<?php

namespace Tests\Feature\Api;

use App\Task;
use App\Stream;
use App\Project;
use App\Objective;
use App\Milestone;
use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TaskControllerTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    public function taskData($resource, $uid, $user)
    {
        return [
            'resource' => $resource,
            'uid' => $uid,
            'assigned_to' => $user->id,
            'name' => 'My amazing task',
            'due_on' => Carbon::tomorrow()->format('d/m/Y'),
        ];
    }

    /** @test */
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

        $task = $this->taskData('project', $project->uid, $user);

        $this->post("/api/tasks", $task)
             ->assertStatus(200)
             ->assertJson([
                 'data' => [
                     'task' => [
                         'name' => $task['name'],
                         'assigned_to' => $user->id,
                     ],
                 ],
             ]);
    }

    /** @test */
    public function a_project_owner_can_remove_a_task_from_a_project()
    {
        $user = $this->authUser();

        $project = factory(Project::class)->create(['owner_id' => $user->id]);

        $project->tasks()->create(factory(Task::class)->make()->toArray());

        $this->delete("/api/tasks/{$project->tasks->last()->uid}")
             ->assertStatus(200);
    }

    /** @test */
    public function a_project_member_can_return_all_tasks_for_a_given_objective()
    {
        $objective = factory(Objective::class)->create();

        $objective->project->members()->attach($this->authUser()->id);

        $objective->tasks()->create(factory(Task::class)->make()->toArray());

        $this->get("/api/tasks?resource=objective&uid={$objective->uid}")
             ->assertStatus(200)
             ->assertJson(['data' => ['tasks' => $objective->tasks->toArray()]]);
    }

    /** @test */
    public function a_project_member_can_store_a_new_task_for_an_objective()
    {
        $user = $this->authUser();

        $objective = factory(Objective::class)->create();

        $objective->project->members()->attach($user->id);

        $task = $this->taskData('objective', $objective->uid, $user);

        $this->post("/api/tasks", $task)
             ->assertStatus(200)
             ->assertJson([
                 'data' => [
                     'task' => [
                         'name' => $task['name'],
                         'assigned_to' => $user->id,
                     ],
                 ],
             ]);
    }

    /** @test */
    public function a_project_owner_can_remove_a_task_from_an_objective()
    {
        $user = $this->authUser();

        $project = factory(Project::class)->create(['owner_id' => $user->id]);

        $objective = factory(Objective::class)->create(['project_id' => $project->id]);

        $objective->tasks()->create(factory(Task::class)->make()->toArray());

        $this->delete("/api/tasks/{$objective->tasks->last()->uid}")
             ->assertStatus(200);
    }

    /** @test */
    public function a_project_member_can_return_all_tasks_for_a_given_milestone()
    {
        $milestone = factory(Milestone::class)->create();

        $milestone->project->members()->attach($this->authUser()->id);

        $milestone->tasks()->create(factory(Task::class)->make()->toArray());

        $this->get("/api/tasks?resource=milestone&uid={$milestone->uid}")
             ->assertStatus(200)
             ->assertJson(['data' => ['tasks' => $milestone->tasks->toArray()]]);
    }

    /** @test */
    public function a_project_member_can_store_a_new_task_for_a_milestone()
    {
        $user = $this->authUser();

        $milestone = factory(Milestone::class)->create();

        $milestone->project->members()->attach($user->id);

        $task = $this->taskData('milestone', $milestone->uid, $user);

        $this->post("/api/tasks", $task)
             ->assertStatus(200)
             ->assertJson([
                 'data' => [
                     'task' => [
                         'name' => $task['name'],
                         'assigned_to' => $user->id,
                     ],
                 ],
             ]);
    }

    /** @test */
    public function a_project_owner_can_remove_a_task_from_an_milestone()
    {
        $user = $this->authUser();

        $project = factory(Project::class)->create(['owner_id' => $user->id]);

        $milestone = factory(Milestone::class)->create(['project_id' => $project->id]);

        $milestone->tasks()->create(factory(Task::class)->make()->toArray());

        $this->delete("/api/tasks/{$milestone->tasks->last()->uid}")
             ->assertStatus(200);
    }

    /** @test */
    public function a_project_member_can_return_all_tasks_for_a_given_stream()
    {
        $stream = factory(Stream::class)->create();

        $stream->project->members()->attach($this->authUser()->id);

        $stream->tasks()->create(factory(Task::class)->make()->toArray());

        $this->get("/api/tasks?resource=stream&uid={$stream->uid}")
             ->assertStatus(200)
             ->assertJson(['data' => ['tasks' => $stream->tasks->toArray()]]);
    }

    /** @test */
    public function a_project_member_can_store_a_new_task_for_a_stream()
    {
        $user = $this->authUser();

        $stream = factory(Stream::class)->create();

        $stream->project->members()->attach($user->id);

        $task = $this->taskData('stream', $stream->uid, $user);

        $this->post("/api/tasks", $task)
             ->assertStatus(200)
             ->assertJson([
                 'data' => [
                     'task' => [
                         'name' => $task['name'],
                         'assigned_to' => $user->id,
                     ],
                 ],
             ]);
    }

    /** @test */
    public function a_stream_owner_can_remove_a_task_from_a_stream()
    {
        $user = $this->authUser();

        $project = factory(Project::class)->create();

        $stream = factory(Stream::class)->create(['project_id' => $project->id]);

        $stream->owners()->attach($user->id);

        $stream->tasks()->create(factory(Task::class)->make()->toArray());

        $this->delete("/api/tasks/{$stream->tasks->last()->uid}")
             ->assertStatus(200);
    }

    /** @test */
    public function a_project_owner_can_remove_a_task_from_a_stream()
    {
        $user = $this->authUser();

        $project = factory(Project::class)->create(['owner_id' => $user->id]);

        $stream = factory(Stream::class)->create(['project_id' => $project->id]);

        $stream->owners()->attach(factory(User::class)->create());

        $stream->tasks()->create(factory(Task::class)->make()->toArray());

        $this->delete("/api/tasks/{$stream->tasks->last()->uid}")
             ->assertStatus(200);
    }

}
