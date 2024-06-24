<?php

namespace Controllers;

use Model\Evento;
use Model\EventosRegistros;
use Model\Registro;
use Model\Usuario;
use MVC\Router;

class DashboardController
{
    public static function index(Router $router)
    {
        // obtener ultimos registros
        $registros = Registro::get(5);
        foreach ($registros as $registro) {
            $registro->usuario = Usuario::find($registro->usuario_id);
        }

        //!CALCULAR LOS INGRESOS
        $virtuales = Registro::total('paquete_id', 2);
        $presenciales = Registro::total('paquete_id', 1);

        $ingresos = ($virtuales * 45.21) + ($presenciales * 187.23);

        //obtener evento conmas y  menos lugares disponibles
        $menos_disponibles = Evento::orderLimit('disponibles', 'ASC', '5');
        $mas_disponibles = Evento::orderLimit('disponibles', 'DESC', '5');
        

        $router->render('admin/dashboard/index', [
            'titulo' => 'Panel de Administración',
            'registros' => $registros,
            'ingresos' => $ingresos,
            'menos_disponibles' => $menos_disponibles,
            'mas_disponibles' => $mas_disponibles
        ]);
    }
}
