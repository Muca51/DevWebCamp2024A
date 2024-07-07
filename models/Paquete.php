<?php

namespace Model;

class Paquete extends ActiveRecord {
    Protected static $tabla = 'paquetes';
    protected static $columnasDB = ['id', 'nombre'];

    public $id;
    public $nombre;

   
}
