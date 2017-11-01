<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MovingItens extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($itens)
    {
        $this->itens = $itens;
    }

    private $itens;

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.moving')
                    ->with([
                            'itens' => $this->itens
                    ]);
    }
}
