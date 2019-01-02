<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DemoEmail extends Mailable
{
    use Queueable, SerializesModels;
     
    /**
     * The demo object instance.
     *
     * @var Demo
     * @var Tipo
     */
    public $demo;
    public $tipo;
   
    //public $distressCall;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    //public function __construct(DistressCall $distressCall)
    public function __construct($demo, $tipo)
    {
        $this->demo = $demo;
        switch($tipo) {
            case 1:
                $this->tipo = 'Conciliación';
                break;
            case 2:
                $this->tipo = 'Bajas';
                break;
        }

        //$this->distressCall=$distressCall;
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
                    ->addTextHeader('Custom-Header', 'Resultados del reporte de '.$this->tipo);
        });

        return $this->from('sysadmin@televisa.com.mx')
                    ->view('correo.demo')
                    -> subject ('Resultado del proceso de '.$this->tipo);
    }
}
