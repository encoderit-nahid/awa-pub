<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $body;
    public $template;
    public $pdf;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $body, $template=null, $pdf=null)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->template = $template;
        $this->pdf = $pdf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->subject($this->subject)
        ->markdown('emails.SendInvoice');

        if($this->template) {
            $mail = $mail->attachData($this->pdf->output(), "AWA-Invoice-2025.pdf", ['mime '=>' application/pdf']);
        }

        return $mail;
    }
}
