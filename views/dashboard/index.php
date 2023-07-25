<?php include_once __DIR__ . '/header-dashboard.php';?>
<?php if(count($proyectos) === 0) { ?>
    <p class="no-proyectos">No hay Proyectos aún <a href="/crear-proyecto">Comineza Creando uno.</a> </p>
<?php } else { ?>
    <ul class="listado-proyectos">
        <?php foreach($proyectos as $proyecto) { ?>
            <li class="proyecto">
            <img class="opciones" src="build/img/menu.svg" alt="imagen menu" data-proyecto="<?php echo $proyecto->id; ?>">
            <ul class="ul-opciones show" data-proyecto="<?php echo $proyecto->id; ?>">
                <form class="form-opcion" method="POST" action="/proyecto/eliminar">
                    <input type="hidden" name="id" value="<?php echo $proyecto->id?>">
                    <li data-proyecto="<?php echo $proyecto->proyecto; ?>">Actualizar</li>
                    <li>Eliminar</li>
                </form>
            </ul>
                

                <a href="/proyecto?id=<?php echo $proyecto->url; ?>"><?php echo $proyecto->proyecto; ?></a>
            </li>
        <?php } ?>
    </ul>
<?php } ?>
<?php include_once __DIR__ . '/footer-dashboard.php';?>





