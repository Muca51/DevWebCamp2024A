<?php

namespace Model;

class Regalo extends ActiveRecord {
    Protected static $tabla = 'Regalos';
    protected static $columnasDB = ['id', 'nombre'];

    public $id;
    public $nombre;
    
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        
                
    }



}