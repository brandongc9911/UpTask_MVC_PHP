<?php include_once __DIR__ . '/header-dashboard.php';?>

<div class="contenedor-sm">
<?php include_once __DIR__ . '/../templates/alertas.php';?>
<a href="/perfil" class="enlace">Volver al Perfil</a>

    <form action="" class="formulario" method="POST" action="/cambiar-password">
        <div class="campo">
            <label for="password_actual">Password Actual</label>
            <input type="password" name="password_actual" placeholder="Password Actual" id="password_actual" >
        </div>

        <div class="campo">
            <label for="password_nuevo">Password Nuevo</label>
            <input type="password" name="password_nuevo" placeholder="Password Nuevo" id="password_nuevo" >
        </div>

        <input type="submit" value="Guardar Cambios">
    </form>

</div>
<?php include_once __DIR__ . '/footer-dashboard.php';?>