<?php

namespace Controllers;

use Model\Categoria;
use Model\Dia;
use Model\Evento;
use Model\Hora;
use Model\Ponente;
use MVC\Router;

class PaginasController
{
    public static function index(Router $router)
    {
        $eventos = Evento::order('hora_id', 'ASC');
        $categorias = Categoria::all('ASC');
        $dias = Dia::all('ASC');

        $eventos_formateados = [];
        foreach ($eventos as $evento) {
            $evento->categoria = Categoria::find($evento->categoria_id);
            $evento->dia = Dia::find($evento->dia_id);
            $evento->hora = Hora::find($evento->hora_id);
            $evento->ponente = Ponente::find($evento->ponente_id);
            /* if ($evento->dia_id === "1" && $evento->categoria_id === "1") {
                $eventos_formateados['conferencias_viernes'][] = $evento; // se guardan los eventos de conferencias del dia viernes
            }
            if ($evento->dia_id === "2" && $evento->categoria_id === "1") {
                $eventos_formateados['conferencias_sabado'][] = $evento; // se guardan los eventos de conferencias del dia viernes
            }
            if ($evento->dia_id === "2" && $evento->categoria_id === "2") {
                $eventos_formateados['workshops_viernes'][] = $evento; // se guardan los eventos de conferencias del dia viernes
            }
            if ($evento->dia_id === "2" && $evento->categoria_id === "2") {
                $eventos_formateados['workshops_sabado'][] = $evento; // se guardan los eventos de conferencias del dia viernes
            }
            */
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

        //!obtener el total de cada bloque
        $ponentes_total = Ponente::total();
        $conferencias_total = Evento::total('categoria_id', 1);
        $workshops_total = Evento::total('categoria_id', 2);

        $ponentes = Ponente::all();

        $router->render('paginas/index', [
            'titulo' => 'Inicio',
            'eventos' => $eventos_formateados,
            'ponentes_total' => $ponentes_total,
            'conferencias_total' => $conferencias_total,
            'workshops_total' => $workshops_total,
            'ponentes' => $ponentes
        ]);
    }
    public static function evento(Router $router)
    {

        $router->render('paginas/devwebcamp', [
            'titulo' => 'Sobre DevWebCamp'
        ]);
    }
    public static function paquetes(Router $router)
    {

        $router->render('paginas/paquetes', [
            'titulo' => 'Paquetes DevWebCamp'
        ]);
    }
    public static function conferencias(Router $router)
    {
        $eventos = Evento::order('hora_id', 'ASC');
        $categorias = Categoria::all('ASC');
        $dias = Dia::all('ASC');

        $eventos_formateados = [];
        foreach ($eventos as $evento) {
            $evento->categoria = Categoria::find($evento->categoria_id);
            $evento->dia = Dia::find($evento->dia_id);
            $evento->hora = Hora::find($evento->hora_id);
            $evento->ponente = Ponente::find($evento->ponente_id);
            /* if ($evento->dia_id === "1" && $evento->categoria_id === "1") {
                $eventos_formateados['conferencias_viernes'][] = $evento; // se guardan los eventos de conferencias del dia viernes
            }
            if ($evento->dia_id === "2" && $evento->categoria_id === "1") {
                $eventos_formateados['conferencias_sabado'][] = $evento; // se guardan los eventos de conferencias del dia viernes
            }
            if ($evento->dia_id === "2" && $evento->categoria_id === "2") {
                $eventos_formateados['workshops_viernes'][] = $evento; // se guardan los eventos de conferencias del dia viernes
            }
            if ($evento->dia_id === "2" && $evento->categoria_id === "2") {
                $eventos_formateados['workshops_sabado'][] = $evento; // se guardan los eventos de conferencias del dia viernes
            }
            */
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
        //debuguear($eventos_formateados);
        $router->render('paginas/conferencias', [
            'titulo' => 'Conferencias & Workshops',
            'eventos' => $eventos_formateados
        ]);
    }
    public static function error(Router $router){

        $router->render('paginas/error', [
            'titulo' => 'Página no encontrada'
        ]);
    }
    

}
