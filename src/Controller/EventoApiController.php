<?php

namespace App\Controller;

use App\Entity\Evento;

class EventoApiController
{
//    private $eventoHandler;
//
//    public function __construct(EventoAdminController $eventoHandler)
//    {
//        $this->eventoHandler = $eventoHandler;
//    }

    public function __invoke(Evento $data): Evento
    {
        //PROCESAR EL JSON DE LA API
//        var_dump($data);die();
        //$data->setDescripcion('EDITADO');
        //var_dump($data1);die();
        return $data;
    }
}
