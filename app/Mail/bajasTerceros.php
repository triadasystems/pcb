<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class bajasTerceros extends Mailable
{
    use Queueable, SerializesModels;
     
    /**
     * The demo object instance.
     *
     * @var Demo
     * 
     */
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    //public function __construct(DistressCall $distressCall)
    public function __construct($data)
    {
        $this->data = $data;
    }
 
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->withSwiftMessage(function ($message) {
            $message->getHeaders()
                    ->addTextHeader('Custom-Header', 'Notificación de baja de autorizadores/responsables');
        });

        return $this->from('sysadmin@televisa.com.mx')
                    ->view('correo.bTerceros')
                    -> subject ('Notificación de baja de autorizadores/responsables');
    }
}
