<?php

namespace Controllers;

use Model\Regalo;
use Model\Registro;

class APIRegalos
{
    public static function index()
    {
        if (!is_admin()) {
            echo json_encode([]);//retornamos un arreglo vacio si no es un admin
            return;
        }

        $regalos = Regalo::all();

        foreach ($regalos as $regalo) {
            $regalo->total = Registro::totalArray([
                'regalo_id' => $regalo->id,
                'paquete_id' => '1'
            ]);
        }
        echo json_encode($regalos); //asi se convierte a json un arreglo
        return;
    }
}
