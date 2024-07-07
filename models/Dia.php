<?php

namespace Model;

class Dia extends ActiveRecord {
    Protected static $tabla = 'dias';
    protected static $columnasDB = ['id', 'nombre'];

    public $id;
    public $nombre;

    public static function index() {

    }
}
