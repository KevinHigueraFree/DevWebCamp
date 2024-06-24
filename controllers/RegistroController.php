<?php

namespace Controllers;

use Model\Categoria;
use Model\Dia;
use Model\Evento;
use Model\EventosRegistros;
use Model\Hora;
use Model\Paquete;
use Model\Ponente;
use Model\Regalo;
use Model\Registro;
use Model\Usuario;
use MVC\Router;

class RegistroController
{
    public static function crear(Router $router)
    {
        if (!is_auth()) {
            header('Location: /');
            return;
        }
     //si esta registrado y tiene paquete gratis o virtual llevar a su boleto
     $registro = Registro::where('usuario_id', $_SESSION['id']);
     if (isset($registro) && ($registro->paquete_id === "3" || $registro->paquete_id === "2") ) {
         header('Location: /boleto?id=' . urlencode($registro->token));
         return;
        }
        
        //si esta registrado y tiene paquete presencial  llevar a las conferencias
        if (isset($registro) && $registro->paquete_id === "1") {
            header('Location: /finalizar-registro/conferencias');
            return;
        }

        $router->render(
            'registro/crear',
            [
                'titulo' => 'Finalizar Registro'
            ]
        );
    }
    public static function gratis(Router $router)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!is_auth()) {
                header('Location: /login');
                return;
            }
            //? substr: corta una cadena, en esete caso de la posicion 0  a la 8
            $token = substr(md5(uniqid(rand(), true)), 0, 8);

            //! crear registro
            $datos = [
                'paquete_id' => 3,
                'pago_id' => '',
                'token' => $token,
                'usuario_id' => $_SESSION['id']
            ];
            $registro = new Registro($datos);
            $resultado = $registro->guardar();
            if ($resultado) {
                header('Location: /boleto?id=' . urlencode($registro->token));
                return;
            }
        }
    }
    public static function boleto(Router $router)
    {

        //! validar la url
        $id = $_GET['id'];
        if (!$id || !strlen($id) === 8) {
            header('Location:/');
            return;
        }

        //!buscar en la base de datos
        $registro = Registro::where('token', $id);
        if (!$registro) {
            header('Location:/');
            return;
        }

        //! llenar las tabals de referencia
        //todo: creamos la variable usuario y le agregamos los datos de la consulta a el modelo usuario por medio de el valor de la variable $regsitro en su campo usuario_id
        $registro->usuario = Usuario::find($registro->usuario_id);
        $registro->paquete = Paquete::find($registro->paquete_id);
        //  debuguear($registro);

        $router->render(
            'registro/boleto',
            [
                'titulo' => 'Asistencia a DevWebCamp',
                'registro' => $registro
            ]
        );
        // Validar que Post no venga vacio
        if (empty($_POST)) {
            echo json_encode([]);
            return;
        }

        // Crear el registro
        $datos = $_POST;
        $datos['token'] = substr(md5(uniqid(rand(), true)), 0, 8);
        $datos['usuario_id'] = $_SESSION['id'];


        try {
            $registro = new Registro($datos);
            $resultado = $registro->guardar();
            echo json_encode($resultado);
        } catch (\Throwable $th) {
            echo json_encode([
                'resultado' => 'error'
            ]);
        }
    }

    public static function pagar(Router $router)
    {


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!is_auth()) {
                header('Location: /login');
                return;
            }

            // Validar que Post no venga vacio
            if (empty($_POST)) {
                echo json_encode([]);
                return;
            }

            // Crear el registro
            $datos = $_POST;
            $datos['token'] = substr(md5(uniqid(rand(), true)), 0, 8);
            $datos['usuario_id'] = $_SESSION['id'];


            try {
                $registro = new Registro($datos);
                $resultado = $registro->guardar();
                echo json_encode($resultado);
            } catch (\Throwable $th) {
                echo json_encode([
                    'resultado' => 'error'
                ]);
            }
        }
    }

    public static function conferencias(Router $router)
    {
        if (!is_auth()) {
            header('Location: /login');
            return;
        }

        //validar que el ususario tenga el plan presencial
        $usuario_id = $_SESSION['id'];
        $registro = Registro::where('usuario_id', $usuario_id);

        //todo: solo los usuarios con el plan  presencial podran ver la pagina
        //  debuguear($registro);
        if (isset($registro) && $registro->paquete_id !== "2") {
            header('Location: /boleto?id=' . urlencode($registro->token));
            return;
        }
        if ($registro->paquete_id !== "1") {
            header('Location: /');
            return;
        }

        // redireccionar a boleto virtual en caso de haber finalizar el registro
        if (isset($registro->regalo_id) && $registro->paquete_id == "1") {
            header('Location: /boleto?id=' . urlencode($registro->token));
            return;
        }

        $eventos = Evento::order('hora_id', 'ASC');
        $categorias = Categoria::all('ASC');
        $dias = Dia::all('ASC');

        $eventos_formateados = [];
        foreach ($eventos as $evento) {
            $evento->categoria = Categoria::find($evento->categoria_id);
            $evento->dia = Dia::find($evento->dia_id);
            $evento->hora = Hora::find($evento->hora_id);
            $evento->ponente = Ponente::find($evento->ponente_id);


            foreach ($categorias  as $categoria) {
                if ($evento->categoria_id === $categoria->id) {
                    foreach ($dias as $dia) {
                        if ($evento->dia_id === $dia->id) {
                            $eventos_formateados[strtolower($evento->categoria->nombre . "_" . $evento->dia->nombre)][] = $evento;
                        }
                    }
                }
            }
        }
        $regalos = Regalo::all('ASC');

        //! manejando el registro mediante post
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //revisar que el usuarios este autenticado
            if (!is_auth()) {
                header('Location: /login');
                return;
            }
            $eventos = explode(',', $_POST['eventos']);
            if (empty($eventos)) {
                echo json_encode(['resultado' => false]);
                return;
            }

            //Obtener registros de usuario
            $registro = Registro::where('usuario_id', $_SESSION['id']);
            if (!isset($registro) || $registro->paquete_id !== "1") {
                echo json_encode(['resultado' => false]);
                return;
            }


            $regalos = $_POST['regalo_id'];

            $eventos_array = [];


            //!validar la disponibilidad de los eventos seleccionados
            foreach ($eventos as $evento_id) {
                $evento = Evento::find($evento_id);


                // COMPROBAR QUE EL EVENTO EXISTA
                if (!isset($evento) || $evento->disponibles === "0") {
                    // debuguear('sin disponibilidad');
                    echo json_encode(['resultado' => false]);
                    return;
                }
                $eventos_array[] = $evento;
            }
            foreach ($eventos_array as $evento) {
                $evento->disponibles -= 1;
                $evento->guardar();

                //!almacenar el registro
                $datos = [
                    'evento_id' => (int)$evento->id, //el (int es castearlos)
                    'registro_id' => (int)$registro->id
                ];

                $registro_usuario = new EventosRegistros($datos);
                $registro_usuario->guardar();
            }
            //!almacenar el regalo
            $registro->sincronizar(['regalo_id' => $_POST['regalo_id']]);
            $resultado = $registro->guardar();
            if ($resultado) {
                echo json_encode([
                    'resultado' => $resultado,
                    'token' => $registro->token
                ]); //lo pasamos al front end con java script
            } else {
                echo json_encode(['resultado' => false]);
            }
            return; //para que ya no siga ejecutando codigo
        }
        $router->render(
            'registro/conferencias',
            [
                'titulo' => 'Elige Workshops y Conferencias',
                'eventos' => $eventos_formateados,
                'regalos' => $regalos
            ]
        );
    }
}
