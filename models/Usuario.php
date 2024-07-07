<?php

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellidoPat', 'apellidoMat', 'email', 'password', 'confirmado', 'token', 'admin'];

    public $id;
    public $nombre;
    public $apellidoPat;
    public $apellidoMat;
    public $email;
    public $password;
    public $password2;
    public $confirmado;
    public $token;
    public $admin;

    public $password_actual;
    public $password_nuevo;

    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellidoPat = $args['apellido'] ?? '';
        $this->apellidoMat = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
        $this->token = $args['token'] ?? '';
        $this->admin = $args['admin'] ?? 0;
    }

    // Validar el Login de Usuarios
    public function validarLogin() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email del Usuario es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password no puede ir vacio';
        }
        return self::$alertas;

    }

    // Validación para cuentas nuevas
    public function validar_cuenta() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        } else {
            $this->nombre = formatearNombrePropio($this->nombre);
        }
        if(!$this->apellidoPat) {
            self::$alertas['error'][] = 'El Apellido Paterno Obligatorio';
        } else {
            $this->apellidoPat = formatearNombrePropio($this->apellidoPat);
        }
        if(!$this->apellidoMat) {
            self::$alertas['error'][] = 'El Apellido Materno es Obligatorio';
        } else {
            $this->apellidoMat = formatearNombrePropio($this->apellidoMat);  
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        } 
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password no puede ir vacio';
        }
        if (strlen($this->password) < 8 || strlen($this->password) > 16) {
            self::$alertas['error'][] = 'El Password debe tener entre 8 y 16 caracteres';
        }
        if (preg_match('/\s/', $this->password)) {
            self::$alertas['error'][] = 'El Password no puede contener espacios';
        }
        if (!preg_match('/[a-z]/', $this->password)) {
            self::$alertas['error'][] = 'El Password debe contener al menos una letra minúscula';
        }
        if (!preg_match('/[A-Z]/', $this->password)) {
            self::$alertas['error'][] = 'El Password debe contener al menos una letra mayúscula';
        }
        if (!preg_match('/\d/', $this->password)) {
            self::$alertas['error'][] = 'El Password debe contener al menos un número';
        }
        if (!preg_match('/[\W_]/', $this->password)) {
            self::$alertas['error'][] = 'El Password debe contener al menos un carácter especial';
        }
        if($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Los password son diferentes';
        }
        return self::$alertas;
    }

    // Valida un email
    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }
        return self::$alertas;
    }

    // Valida el Password 
    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password no puede ir vacio';
        }
        if (strlen($this->password) < 8 || strlen($this->password) > 16) {
            self::$alertas['error'][] = 'El Password debe tener entre 8 y 16 caracteres';
        }
        if (preg_match('/\s/', $this->password)) {
            self::$alertas['error'][] = 'El Password no puede contener espacios';
        }
        if (!preg_match('/[a-z]/', $this->password)) {
            self::$alertas['error'][] = 'El Password debe contener al menos una letra minúscula';
        }
        if (!preg_match('/[A-Z]/', $this->password)) {
            self::$alertas['error'][] = 'El Password debe contener al menos una letra mayúscula';
        }
        if (!preg_match('/\d/', $this->password)) {
            self::$alertas['error'][] = 'El Password debe contener al menos un número';
        }
        if (!preg_match('/[\W_]/', $this->password)) {
            self::$alertas['error'][] = 'El Password debe contener al menos un carácter especial';
        }
        if($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Los password son diferentes';
        }
        return self::$alertas;
    }

    public function nuevo_password() : array {
        if(!$this->password_actual) {
            self::$alertas['error'][] = 'El Password Actual no puede ir vacio';
        }
        if(!$this->password_nuevo) {
            self::$alertas['error'][] = 'El Password Nuevo no puede ir vacio';
        }
        if(strlen($this->password_nuevo) < 6) {
            self::$alertas['error'][] = 'El Password debe contener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    // Comprobar el password
    public function comprobar_password() : bool {
        return password_verify($this->password_actual, $this->password );
    }

    // Hashea el password
    public function hashPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Generar un Token
    public function crearToken() : void {
        $this->token = uniqid();
    }
}