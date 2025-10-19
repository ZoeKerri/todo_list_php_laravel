<?php

namespace App\Console\Commands;

use App\Jobs\SendTaskNotificationJob;
use App\Models\PersonalTask;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendTaskNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:send-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email notifications for tasks due soon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $oneHourFromNow = $now->copy()->addHour();

        // Find tasks that are due within the next hour and have notification time set
        $tasks = PersonalTask::where('completed', false)
            ->whereNotNull('notification_time')
            ->whereBetween('due_date', [$now, $oneHourFromNow])
            ->with(['user', 'category'])
            ->get();

        foreach ($tasks as $task) {
            // Check if notification time matches current time (within 5 minutes)
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
