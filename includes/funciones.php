<?php

function debuguear($variable): string
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}
function s($html): string
{
    $s = htmlspecialchars($html);
    return $s;
}
//!VERIFICAR SI LA PAGINA CONTIENE EL PATH (URL) QUE LE ESTAMOS PASANDO
function pagina_actual($path)
{
    return str_contains($_SERVER['PATH_INFO'] ?? '/', $path) ? true : false;
}

function is_auth(): bool
{
    if (!isset($_SESSION)) {
        session_start();
    }
    return isset($_SESSION['nombre']) && !empty($_SESSION);
}
function is_admin(): bool
{
    if (!isset($_SESSION)) {
        session_start();
    }
    return isset($_SESSION['admin']) && !empty($_SESSION['admin']);
}

function aos_animacion(): void
{
    $efectos = ['fade-up', 'fade-down', 'fade-left', 'fade-right', 'flip-left', 'flip-right', 'zoom-in', 'zoom-in-up', 'zoom-in-down'];
    $efecto = array_rand($efectos, 1); //? array_rand: toma una posicion aeatorio de un arreglo y el 1 hace que devuelva solo una posicion
    echo ' data-aos="' . $efectos[$efecto] . '" '; //todo $efectos[$efecto] accede al valor de la posicion del arreglo de efectos
}
