<?php

namespace Controllers;

use Classes\Paginacion;
use Model\Ponente;
use MVC\Router;
use Intervention\Image\ImageManagerStatic as Image; //para subir las imagenes

class PonentesController
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
            header("Location:/admin/ponentes?page=1");
        }
        $registros_por_pagina = 5;
        $total_registros = Ponente::total();
        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total_registros);
        // debuguear($paginacion->pagina_siguiente());
        if ($paginacion->total_paginas() < $pagina_actual) {
            header("Location:/admin/ponentes?page=1");
        }
        $ponentes = Ponente::paginar($registros_por_pagina, $paginacion->offset());
        //debuguear($ponentes);
        // $ponentes=[];
        if (!is_admin()) {
            header('Location:/login');
        }
        //  debuguear(is_admin());
        $router->render('admin/ponentes/index', [
            'titulo' => 'Ponentes / Conferencistas',
            'ponentes' => $ponentes,
            'paginacion' => $paginacion->paginacion()
        ]);
    }

    public static function crear(Router $router)
    {
        if (!is_admin()) {
            header('Location:/login');
        }
        $alertas = [];
        $ponente = new Ponente($_POST);
        // debuguear($ponente);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!is_admin()) {
                header('Location:/login');
            }
            //! leer imagen
            //todo enctype="multipart/form-data": es importnate tenerlo en el input del formulario para que funcione lo de la imagen
            if (!empty($_FILES['imagen']['tmp_name'])) {
                $carpeta_imagenes = '../public/img/speakers';
                //!crear carpeta
                if (!is_dir($carpeta_imagenes)) {
                    mkdir($carpeta_imagenes, 0755, true);
                }
                $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('png', 80);
                $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('webp', 80);

                $nombre_imagen = md5(uniqid(rand(), true)); //generar nombre de la imagen
                $_POST['imagen'] = $nombre_imagen;
            }
            //? json_encode convierte el array on string
            //? JSON_UNESCAPED_SLASHES es para ELIMINAR las diagonales invertidad
            $_POST['redes'] = json_encode($_POST['redes'], JSON_UNESCAPED_SLASHES);
            //Debuguear($_POST['redes']);
            $ponente->sincronizar($_POST);
            //debuguear($ponente);


            //!validar
            $alertas = $ponente->validar();
            //debuguear($alertas);
            //! guardar imagen en registro
            if (empty($alertas)) {
                //? guardar imagenes
                $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . '.png'); //almacenar en el servidor
                $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . '.webp'); //almacenar en el servidor

                //!guardar en database
                $resultado = $ponente->guardar();
                //debuguear($resultado);
                if ($resultado) {
                    header('Location:/admin/ponentes');
                }
            }
        }
        $router->render('admin/ponentes/crear', [
            'titulo' => 'Registrar Ponente',
            'alertas' => $alertas,
            'ponente' => $ponente,
            'redes' => json_decode($ponente->redes)
        ]);
    }
    public static function editar(Router $router)
    {
        if (!is_admin()) {
            header('Location:/login');
        }
        $alertas = [];
        //! validar id
        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            header('Location:/admin/ponentes');
        }



        //! obtener ponente a editar
        $ponente = Ponente::find($id);

        if (!$ponente) {
            header('Location:/admin/ponentes');
        }

        $ponente->imagen_actual = $ponente->imagen;
        //debuguear($ponente);


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!is_admin()) {
                header('Location:/login');
            }
            //! leer imagen
            //todo enctype="multipart/form-data": es importnate tenerlo en el input del formulario para que funcione lo de la imagen
            if (!empty($_FILES['imagen']['tmp_name'])) {
                $carpeta_imagenes = '../public/img/speakers';
                //!crear carpeta
                if (!is_dir($carpeta_imagenes)) {
                    mkdir($carpeta_imagenes, 0755, true);
                }
                $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('png', 80);
                $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('webp', 80);

                $nombre_imagen = md5(uniqid(rand(), true)); //generar nombre de la imagen
                $_POST['imagen'] = $nombre_imagen;
            } else {
                $_POST['imagen'] = $ponente->imagen_actual;
            }
            $_POST['redes'] = json_encode($_POST['redes'], JSON_UNESCAPED_SLASHES);
            //debuguear($_POST);
            $ponente->sincronizar($_POST);
            $alertas = $ponente->validar();
            if (empty($alertas)) {
                if (isset($nombre_imagen)) {
                    $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . '.png'); //almacenar en el servidor
                    $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . '.webp'); //almacenar en el servidor
                }
                $resultado = $ponente->guardar();
                if (!$resultado) {
                    header('Location:/admin/ponentes');
                }
            }
        }

        //debuguear($ponente->redes);
        $router->render('admin/ponentes/editar', [
            'titulo' => 'Actualizar Ponente',
            'alertas' => $alertas,
            'ponente' => $ponente,
            'redes' => json_decode($ponente->redes)
        ]);
    }
    public static function eliminar()
    {
       
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!is_admin()) {
                header('Location:/login');
            }
            $id = $_POST['id'];
            $ponente = Ponente::find($id);
            if (!isset($ponente)) {
                header('Location:/admin/ponentes');
            }
            $resultado = $ponente->eliminar();
            if ($resultado) {
                header('Location:/admin/ponentes');
            }
            debuguear($ponente);
        }
    }
}
