<main class="auth">

    <h2 class="auth__heading"><?php echo $titulo; ?></h2>
    <p class="auth__text">Registrate en DevWebCamp</p>

    <?php
        require_once __DIR__ . '/../templates/alertas.php';
    ?>


    <form method="POST" action="/registro" class="formulario">
        <div class="formulario__campo">
            <label class="formulario__label" for="nombre">Nombre</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Tu nombre"
                id="nombre"
                name="nombre"
                value="<?php echo $usuario->nombre; ?>"
            />
        </div>

        <div class="formulario__campo">
            <label class="formulario__label" for="apellidoPat">Apellido Paterno</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Tu Apellido Paterno"
                id="apellidoPat"
                name="apellidoPat"
                value="<?php echo $usuario->apellidoPat; ?>"
            />
        </div>

        <div class="formulario__campo">
            <label class="formulario__label" for="apellidoMat">Apellido Materno</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Tu Apellido Materno"
                id="apellidoMat"
                name="apellidoMat"
                value="<?php echo $usuario->apellidoMat; ?>"
            />
        </div>

        <div class="formulario__campo">
            <label class="formulario__label" for="email">Email</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Tu Email"
                id="email"
                name="email"
                value="<?php echo $usuario->email; ?>"
            />
        </div>

        <div class="formulario__campo">
            <label class="formulario__label" for="password">Password</label>
            <input 
                type="password"
                class="formulario__input"
                placeholder="Tu password"
                id="password"
                name="password"
            />
        </div>

        <div class="formulario__campo">
            <label class="formulario__label" for="password2">Confirma tu Password</label>
            <input 
                type="password"
                class="formulario__input"
                placeholder="Repite tu password"
                id="password2"
                name="password2"
            />
        </div>

        <input type="submit" class="formulario__submit" value="Crear Cuenta">


    </form>

    <div class="acciones">
        <a href="/login" class="acciones__enlace">¿Ya tienes una cuenta? Inicia Sesión</a>
        <a href="/olvide" class="acciones__enlace">¿Olvidaste tu password?</a>
    </div>

</main>