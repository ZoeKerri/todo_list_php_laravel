<?php

namespace App\Console\Commands;

use App\Jobs\SendTaskNotificationJob;
use App\Models\PersonalTask;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendTaskNotifications extends Command
{
    protected $signature = 'tasks:send-notifications';

    protected $description = 'Send email notifications for tasks due soon';

    public function handle()
    {
        $now = Carbon::now();
        $oneHourFromNow = $now->copy()->addHour();

        $tasks = PersonalTask::where('completed', false)
            ->whereNotNull('notification_time')
            ->whereBetween('due_date', [$now, $oneHourFromNow])
            ->with(['user', 'category'])
            ->get();

        foreach ($tasks as $task) {
            $notificationTime = Carbon::parse($task->notification_time);
            $currentTime = Carbon::now();
            
            if ($notificationTime->diffInMinutes($currentTime) <= 5) {
                SendTaskNotificationJob::dispatch($task);
                $this->info("Notification queued for task: {$task->title}");
            }
        }

        $this->info('Task notification check completed.');
    }
}
