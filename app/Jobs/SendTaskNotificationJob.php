<?php

namespace App\Jobs;

use App\Mail\TaskNotificationMail;
use App\Models\PersonalTask;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTaskNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public PersonalTask $task
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->task->user->email)
            ->send(new TaskNotificationMail($this->task));
    }
}
