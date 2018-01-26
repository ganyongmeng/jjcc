<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Taskmail extends Mailable
{
    use Queueable, SerializesModels;
    private $msg;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($msg = "")
    {
        $this->msg = $msg;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return $this->view('mailtemp.taskmail')->with(['msg'=>$this->msg]);
    }
}
