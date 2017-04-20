<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\MailQueue;

class Message extends Mailable
{
    use Queueable, SerializesModels;

    public $mailmessage, $list, $entry;

    private $mailqueue;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(MailQueue $mailqueue)
    {
        $this->mailqueue = $mailqueue;
        $this->mailmessage = $mailqueue->message;
        $this->list = $mailqueue->message->list;
        $this->entry = $mailqueue->entry;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->subject($this->mailmessage->subject)
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.message')
                    ->withSwiftMessage(function($message) {
                        $message->getHeaders()
                                ->addTextHeader('X-Mailgun-Variables', json_encode([
                                        'mailqueue' => $this->mailqueue->id,
                                        'mailmessage' => $this->mailmessage->id,
                                        'entry' => $this->entry->id,
                                    ]));
                        });
        }
}
