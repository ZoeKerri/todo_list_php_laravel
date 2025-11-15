<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DueTaskMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $task,        // nhận luôn cả PersonalTask hoặc TeamTask
        public $user,        // người nhận email
        public $type         // "personal" hoặc "team"
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
            view: 'emails.task-due',
            with: [
                'task' => $this->task,
                'user' => $this->user,
                'type' => $this->type,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
