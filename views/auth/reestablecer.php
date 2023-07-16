<div class="contenedor reestablecer">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php';?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nuevo password</p>
        <?php include_once __DIR__ . '/../templates/alertas.php';?>

        <?php if($mostrar) { ?>

        <form class="formulario" method="POST" >
            <div class="campo">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="email">
            </div>

            <input type="submit" class="boton" value="Guardar Password">
        </form>
        <?php } ;?>
        <div class="acciones">
            <a href="/">¿Ya tienes cuenta ? Iniciar sesión</a>
            <a href="/crear">¿Aún no tienes una cuenta? obtener una</a>
        </div>
    </div>
</div>