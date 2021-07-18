<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $templates;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($templates)
    {
        $this->templates = $templates;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject  = $this->templates['email_subject'];
        $from     = $this->templates['email_from'];
        
        return $this->from($from)
                    ->subject($subject)
                    ->view('order.invoice.order_invoice')
                    ->with('data', $this->templates);
    }
}
