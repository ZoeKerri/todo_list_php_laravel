<?php

namespace App\Mail;

use App\Models\PersonalTask;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PersonalTask $task
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Task Reminder: ' . $this->task->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.task-notification',
            with: [
                'task' => $this->task,
                'user' => $this->task->user,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
