<?php 

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password','token', 'confirmado'];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $password_actual;
    public $password_nuevo;
    public $token;
    public $confirmado;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? "";
        $this->password = $args['password'] ?? "";
        $this->password2 = $args['password2'] ?? "";
        $this->password_actual = $args['password_actual'] ?? "";
        $this->password_nuevo = $args['password_nuevo'] ?? "";
        $this->email = $args['email'] ?? "";
        $this->token = $args['token'] ?? "";
        $this->confirmado = $args['confirmado'] ?? 0;

    }

    public function validarLogin() : array{
        if(!$this->email){
        self::$alertas['error'][]='El email del Usuario es obligatorio';
        }

        if(!$this->password){
        self::$alertas['error'][]='El Password no puede ir vacio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
        self::$alertas['error'][] = "Email no valido";

        }
       return self::$alertas;

    }

    public function validarNuevaCuenta() : array
    {
       if(!$this->nombre){
        self::$alertas['error'][]='El nombre del Usuario es obligatorio';
       }
       if(!$this->email){
        self::$alertas['error'][]='El email del Usuario es obligatorio';
       }

       if(!$this->password){
        self::$alertas['error'][]='El Password no puede ir vacio';
       }

       if(strlen($this->password) < 6){
        self::$alertas['error'][]='El Password debe contener al menos 6 caracteres';
       }

       if($this->password !== $this->password2){
        self::$alertas['error'][]='Los Passwords son diferentes';

       }

       return self::$alertas;
    }

    public function validarEmail() : array {
        if(!$this->email){
            self::$alertas['error'][] = "El Email es Obligatorio";
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = "Email no valido";

        }
        return self::$alertas;
    }

    public function validarPassword() : array{
        if(!$this->password){
            self::$alertas['error'][]='El Password no puede ir vacio';
           }
    
           if(strlen($this->password) < 6){
            self::$alertas['error'][]='El Password debe contener al menos 6 caracteres';
           }

           return self::$alertas;
    }

    public function validar_perfil() : array {
        if(!$this->nombre){
            self::$alertas['error'][]='El nombre del Usuario es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][]='El email del Usuario es obligatorio';
        }
        return self::$alertas;

    
    }
    // NO SE PUEDE UTILIZAR THIS CUANDO LA FUNCION ES ESTATICA
    public  function nuevo_password() : array{
        if(!$this->password_actual){
            self::$alertas['error'][]='El Password actual no puede ir vacio';

        }
        if(!$this->password_nuevo){
            self::$alertas['error'][]='El Password nuevo no puede ir vacio';

        }
        if(strlen($this->password_nuevo) < 6){
            self::$alertas['error'][]='El Password debe contener almenos 6 caracteres';

        }
        return self::$alertas;

    }
    public function comprobar_password() : bool {
        return password_verify($this->password_actual,$this->password);
    }

    public function hashPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // GENERAR UN TOKEN

    public function crearToken() : void {
        $this->token = uniqid();
    }
}