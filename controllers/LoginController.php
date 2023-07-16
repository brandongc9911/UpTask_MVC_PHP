<?php 

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router){
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            $usuario = new Usuario($_POST);

            $alertas = $usuario->validarLogin();

            if(empty($alertas)){
                // VERIFICAR  QUE EL USUARIO EXISTA
                $usuario = Usuario::where('email', $usuario->email);

                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta('error', 'El Usuario no existe o no esta confirmado');
                }else {
                    // EL USUARIO EXISTE
                    if(password_verify($_POST["password"], $usuario->password)){
                        // Iniciar sesion
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email; 
                        $_SESSION['login'] = true; 

                        header('Location: /dashboard');

                    }else{
                    Usuario::setAlerta('error', 'Password incorrecto');

                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/login',[
            'titulo' => 'Iniciar sesión',
            'alertas'=> $alertas
        ]);
    }

    public static function logout(Router $router){
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    public static function crear(Router $router){
        $alertas = [];
        $usuario = new Usuario;
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            $existeUsuario = Usuario::where('email', $usuario->email);
            
            if(empty($alertas)){
                if($existeUsuario){
                    Usuario::setAlerta('error', 'El usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                }else{
                    // HASHEAR PASSWORD
                    $usuario->hashPassword();
                    
                    // ELIMINAR PASSWORD2
                    // FUNCION QUE ELIMINA UN ELEMENTO DEL OBJETO
                    unset($usuario->password2);
                    
                    $usuario->crearToken();
                  
                    // CREAR UN NUEVO USUARIO

                    $resultado = $usuario->guardar();

                    // ENVIAR EMAIL
                    $email = new Email($usuario->email, $usuario->nombre,$usuario->token);
                    $email->enviarConfirmacion();

                    if($resultado){
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear',[
            'titulo' => 'Crear cuenta',
            'usuario'=>$usuario,
            'alertas'=>$alertas
        ]);
    }

    public static function olvide(Router $router){
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)){
                // BUSCAR EL USUARIO
                $usuario = Usuario::where('email', $usuario->email);

                if($usuario && $usuario->confirmado){
                    // GENERAR UN NUEVP TOKEN
                    $usuario->crearToken();
                    unset($usuario->password2);
                    // ACTUALIZAR EL USUARIO
                    $usuario->guardar();
                    // ENVIAR EL EMAIL
                    $email = new Email( $usuario->email,$usuario->nombre, $usuario->token );
                    $email->enviarInstrucciones();

                    // IMPRIMIR LA ALERTA
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');

                }else{
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();


        $router->render('auth/olvide',[
            'titulo' => 'Olvide',
            'alertas'=>$alertas
        ]);
    }

    public static function reestablecer(Router $router){
        $token = s($_GET['token']);
        $mostrar = true;

        if(!$token) header('Location: /');

        // IDENTIFICAR EL USUARIO CON EL ESTE TOKEN
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido');
            $mostrar = false;

        }

        $alertas = Usuario::getAlertas();
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            // AÑADIR UN NUEVO PASSWORD
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPassword();

            if(empty($alertas)){
                // HASHERAR EL NUEVO PASSWORD
                $usuario->hashPassword();


                // ELIMINAR EL TOKEN

                $usuario->token = null;
                // GUARDAR EL USUARIO
                $resultado = $usuario->guardar();

                // REDIRECCIONAR
                if($resultado){
                    header('Location:/');
                }
            }

        }

        $router->render('auth/reestablecer',[
            'titulo' => 'Reestablecer Password',
            'alertas'=> $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje',[
            'titulo' => 'Cuenta creada exitosamente'
        ]);
    }

    public static function confirmar(Router $router){
        $token = $_GET['token'];
        if(!$token){
            header('Location: /');
        }
        // Encontrar al usuario con este token
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido');
        }else{
            // CONFIRMAR CUENTA
            $usuario->confirmado = 1;
            unset($usuario->password2);
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'cuenta comprobada correctamente');

            
        }
       
        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar',[
            'titulo' => 'Confirmar',
            'alertas'=>$alertas
        ]);
    }
}