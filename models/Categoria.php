<?php

namespace Model;

class Categoria extends ActiveRecord {
    Protected static $tabla = 'categorias';
    protected static $columnasDB = ['id', 'nombre'];

    public $id;
    public $nombre;

   
}
