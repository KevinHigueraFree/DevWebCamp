<?php

namespace Controllers;

use Classes\Paginacion;
use Model\Paquete;
use Model\Registro;
use Model\Usuario;
use MVC\Router;

class RegistradosController
{
    public static function index(Router $router)
    {
        if (!is_admin()) {
            header('Location:/login');
        }

        $pagina_actual = $_GET['page'];
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
        //! validacion que la pagina existe
        if (!$pagina_actual || $pagina_actual < 1) {
            header("Location:/admin/registrados?page=1");
        }
        $registros_por_pagina = 5;
        $total_registros = Registro::total();
        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total_registros);
        // debuguear($paginacion->pagina_siguiente());
        if ($paginacion->total_paginas() < $pagina_actual) {
            header("Location:/admin/registrados?page=1");
        }
        $registros = Registro::paginar($registros_por_pagina, $paginacion->offset());
        foreach ($registros as $registro) {
            $registro->usuario = Usuario::find($registro->usuario_id);
            $registro->paquete = Paquete::find($registro->paquete_id);
        }


        //debuguear($registros);
        // $ponentes=[];
        if (!is_admin()) {
            header('Location:/login');
        }
        $router->render('admin/registrados/index', [
            'titulo' => 'Usuarios Registrados',
            'paginacion' => $paginacion->paginacion(),
            'registros' => $registros
        ]);
    }
}
