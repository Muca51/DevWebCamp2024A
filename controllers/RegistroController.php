<?php

namespace Controllers;

use Model\Dia;
use Model\Hora;
use MVC\Router;
use Model\Evento;
use Model\Paquete;
use Model\Ponente;
use Model\Usuario;
use Model\Registro;
use Model\Categoria;
use Model\EventosRegistros;
use Model\Regalo;


class RegistroController {
    
    public static function crear(Router $router) {

        if(!is_auth()) {
            header('Location: /');
            return;
        }

        //Verificando si el usuario ya esta registrado

        $registro = Registro::where('usuario_id', $_SESSION['id']);
        

        if(isset($registro) && ($registro->paquete_id === "3" || $registro->paquete_id === "2" )) {
            header('Location: /boleto?id=' . urlencode($registro->token));
            return;
        }



        if(isset($registro) && $registro->paquete_id === "1") {
            header('Location: /finalizar-registro/conferencias');
            return;
        }
       

        $router->render('registro/crear', [
            'titulo' => 'Finalizar Registro'
            

        ]);

    }

    public static function gratis() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(!is_auth()) {
                header('Location: /login');
                return;
            }

            //Verificar si el usuario ya esta registrado
            $registro = Registro::where('usuario_id', $_SESSION['id']);
            if(isset($registro) && $registro->paquete_id === "3") {
                header('Location: /boleto?id=' . urlencode($registro->token));
                return;
            }
             $token = substr( md5( uniqid( rand(), true)), 0, 8);
             

             //Crear Registro

             $datos = [
                'paquete_id' => 3,
                'pago_id' => '',
                'token' => $token,
                'usuario_id' => $_SESSION['id']
            ];

             $registro = new Registro($datos);
             $resultado = $registro->guardar();

             if($resultado) {
                header('Location: /boleto?id=' . urlencode($registro->token));
                return;
            }

             
        }

    }

    public static function boleto(Router $router) {

        //Validar URL
        $id = $_GET['id'];

        if(!$id || !strlen($id) === 8) {
            header('Location: /');
            return;
        }

        //Buscar token en base de datos

        $registro = Registro::where('token', $id);
        if(!$registro) {
            header('Location: /');
            return;
        }

        //Llenar las tablas de referencia

        $registro->usuario = Usuario::find($registro->usuario_id);
        $registro->paquete = Paquete::find($registro->paquete_id);

        

        $router->render('registro/boleto', [
            'titulo' => 'Asistencia a DevWebCamp',
            'registro' => $registro,
            
        ]);

    }

    public static function pagar(Router $router) {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(!is_auth()) {
                header('Location: /login');
                return;
            }

            

            // Validar que Post no venga vacio
            if(empty($_POST)) {
                echo json_encode([]);
                return;
            }
            

            // Crear el registro
            $datos = $_POST;
            $datos['token'] = substr( md5(uniqid( rand(), true )), 0, 8);
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

    public static function conferencias(Router $router) {

        if(!is_auth()) {
            header('Loation: /Login');
            return;
        }


        //Validar que el usuario tenga el plan presencial

        $usuario_id = $_SESSION['id'];
        $registro = Registro::where('usuario_id', $usuario_id);

        if($registro) {
            $registro_id = $registro->id;

            //Buscar en la tabla eventos_registros si existe un registro con el registro_id
            $eventoRegistrado = EventosRegistros::where('registro_id', $registro_id);

            
            if ($registro->paquete_id === "1" && $eventoRegistrado !== null) {
                header('Location: /boleto?id=' . urlencode($registro->token));
                return;
            }
        }

        if(isset($registro) && $registro->paquete_id === "2") {
            header('Location: /boleto?id=' . urlencode($registro->token));
            return;

        }

        
        if($registro->paquete_id !== "1") {
            header('Location: /');
            return;
        }

        //Redirección a usuario con paquete 2 al boleto virtual en caso de haber concluído su registro

        if(isset($registro->regalo_id) && $registro->paquete_id === "2") {

            header('Location: /boleto?id=' . urlencode($registro->token));
            return;

        }

        $eventos = Evento::ordenar('hora_id', 'ASC');
        
        $eventos_formateados = [];

        foreach($eventos as $evento) {
            $evento->categoria = Categoria::find($evento->categoria_id);
            $evento->dia = Dia::find($evento->dia_id);
            $evento->hora = Hora::find($evento->hora_id);
            $evento->ponente = Ponente::find($evento->ponente_id);

            if($evento->dia_id === "1" && $evento->categoria_id=== "1") {//Conferencias Viernes
                $eventos_formateados['conferencias_v'][] = $evento;
            }

            if($evento->dia_id === "2" && $evento->categoria_id=== "1") {//Conferencias Sábado
                $eventos_formateados['conferencias_s'][] = $evento;
            }

            if($evento->dia_id === "1" && $evento->categoria_id=== "2") {//WorkShops Viernes
                $eventos_formateados['workshops_v'][] = $evento;
            }

            if($evento->dia_id === "2" && $evento->categoria_id=== "2") {//WorkShops Sábado
                $eventos_formateados['workshops_s'][] = $evento;
            }
        }

        $regalos = Regalo::all('ASC');

        //Manejando el registro mediante $_POST

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            //Revisar que el usuario este autenticado
            if(!is_auth()) {
                header('Loation: /Login');
                return;
            }
            
            //Obteniendo los eventos

            $eventos = explode(',', $_POST['eventos']);
            if(empty($eventos)) {
                echo json_encode(['resultado' => false]);
                return;
            }
            
            //Obtener el registro del usuario

            $registro = Registro::where('usuario_id', $_SESSION['id']);
            if(!isset($registro) || $registro->paquete_id !== "1") {
                echo json_encode(['resultado' => false]);
                return;
            }

            //Validar la disponibilidad de los eventos seleccionados
            $eventos_array = [];
            foreach($eventos as $evento_id) {
                $evento = Evento::find($evento_id);

                //Comprobar que el evento exista
                if(!isset($evento) || $evento->disponibles === "0") {
                    echo json_encode(['resultado' => false]);
                    return;
                }

                $eventos_array[] = $evento; 

            }

            //Ya confirmado la existencia del evento y disponibilidad de lugares

            foreach($eventos_array as $evento) {
                $evento->disponibles -= 1;
                $evento->guardar();

                //Almacenar el registro

                $datos = [
                    'evento_id' => (int) $evento->id,
                    'registro_id' => (int) $registro->id

                ];

                $registro_usuario = new EventosRegistros($datos);
                
                $registro_usuario->guardar();
                
            }

            //Almacenar el regalo

            $registro->sincronizar(['regalo_id' => $_POST['regalo_id']]);
            $resultado = $registro->guardar();

            if($resultado) {
                echo json_encode([
                    'resultado' => $resultado,
                    'token' => $registro->token
                ]);

            } else {
                echo json_encode(['resultado' => false]);
            }

            return;

        }

        $router->render('registro/conferencias', [
            'titulo' => 'Elige Workshops y Conferencias',
            'eventos' => $eventos_formateados,
            'regalos' => $regalos
        ]);

    }

    //Función Pública para condicionar la selección de eventos y horarios

    public static function obtenerEvento(Router $router) {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $eventoId = $_GET['id'] ?? null;
            if ($eventoId) {
                $evento = Evento::find($eventoId);
                if ($evento) {
                    echo json_encode([
                        'dia_id' => $evento->dia_id,
                        'hora_id' => $evento->hora_id
                    ]);
                } else {
                    echo json_encode(['error' => 'Evento no encontrado']);
                }
            } else {
                echo json_encode(['error' => 'ID de evento no proporcionado']);
            }
        }
    }
}


