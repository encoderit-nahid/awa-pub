<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddProjectMail extends Mailable
{
    use Queueable, SerializesModels;
    public $project_name, $user_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($project_name, $user_name)
    {
        $this->project_name = $project_name;
        $this->user_name = $user_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Dein Projekt wurde erfolgreich hinzugefügt!")->markdown('emails.add-project');
    }
}
