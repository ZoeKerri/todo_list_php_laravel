<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PersonalTask;
use App\Models\TeamTask;
use App\Mail\DueTaskMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendDueTasks extends Command
{
    protected $signature = 'tasks:send-due';
    protected $description = 'Send due email notifications for both personal and team tasks';

    public function handle()
    {
        $now = Carbon::now();

        // PERSONAL TASK
        $personalTasks = PersonalTask::where('completed', false)
            ->where('due_date', '<=', $now)
            ->get();

        foreach ($personalTasks as $task) {
            Mail::to($task->user->email)
                ->send(new DueTaskMail($task, $task->user, 'personal'));
        }

        // TEAM TASK
        $teamTasks = TeamTask::where('is_completed', false)
            ->where('deadline', '<=', $now)
            ->get();

        foreach ($teamTasks as $task) {
            $user = $task->assignedUser(); // kiểm tra lại quan hệ trong model

            if ($user) {
                Mail::to($user->email)
                    ->send(new DueTaskMail($task, $user, 'team'));
            }
        }

        return 0;
    }
}
