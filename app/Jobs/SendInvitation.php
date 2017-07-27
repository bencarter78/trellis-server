<?php

namespace App\Jobs;

use App\Project;

use Illuminate\Bus\Queueable;
use App\Mail\ProjectInvitation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendInvitation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;

    protected $project;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, Project $project)
    {
        $this->email = $email;
        $this->project = $project;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)
            ->send(new ProjectInvitation($this->project));
    }
}
