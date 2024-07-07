<?php

namespace Model;

class Hora extends ActiveRecord {
    Protected static $tabla = 'horas';
    protected static $columnasDB = ['id', 'hora'];

    public $id;
    public $hora;

    public static function index() {

    }
}
