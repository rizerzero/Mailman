<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\MailQueue;
use Blade;

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
        $this->list = $mailqueue->message->mailList;
        $this->entry = $mailqueue->entry;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $subject = eval('?>'.Blade::compileString($this->mailmessage->subject));

        if($this->mailmessage->text_only) {
            return $this->subject($subject)
                    ->from($this->list->from_email, $this->list->from_name)
                    ->text('emails.text')
                    ->withSwiftMessage(function($message) {
                        $message->getHeaders()
                                ->addTextHeader('X-Mailgun-Variables', json_encode([
                                        'mailqueue' => $this->mailqueue->id,
                                        'mailmessage' => $this->mailmessage->id,
                                        'entry' => $this->entry->id,
                                    ]));
                        });;
        } else {
            return $this->subject($subject)
                    ->from($this->list->from_email, $this->list->from_name)
                    ->view('emails.message')
                    ->withSwiftMessage(function($message) {
                        $message->getHeaders()
                                ->addTextHeader('X-Mailgun-Variables', json_encode([
                                        'mailqueue' => $this->mailqueue->id,
                                        'mailmessage' => $this->mailmessage->id,
                                        'entry' => $this->entry->id,
                                    ]));
                        });;
        }


    }
}
