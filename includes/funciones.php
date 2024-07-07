<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}


function formatearNombrePropio($nombre) {
    // Convertir todo el texto a minúsculas
    $nombre = strtolower($nombre);
    
    // Eliminar los espacios en blanco al principio y al final
    $nombre = trim($nombre);

    // Eliminar espacios dobles (o más) entre palabras
    $nombre = preg_replace('/\s+/', ' ', $nombre);

    // Convertir la primera letra de cada palabra a mayúscula
    $nombreFormateado = ucwords($nombre);

    return $nombreFormateado;
}

function pagina_actual($path) : bool {//Sombrear la pagina en la que estamnos trabajando
    return str_contains( $_SERVER['PATH_INFO'] ?? '/', $path) ? true : false;
}

function is_auth() : bool {
    if(!isset($_SESSION)) {
        session_start();
    }
    return isset($_SESSION['nombre']) && !empty($_SESSION);
}

function is_admin() : bool {
    if(!isset($_SESSION)) {
        session_start();
    }
    return isset($_SESSION['admin']) && !empty($_SESSION['admin']);
}

function aos_animacion () : void {
    $efectos = ['fade-up', 'fade-down', 'fade-right', 'fade-left', 'flip-left', 'flip-right', 'zoom-in', 'zoom-in-up', 'zoom-in-down', 'zoom-out',];
    $efecto = array_rand($efectos, 1);

    echo ' data-aos ="' . $efectos[$efecto] . '" ' ;    
}







