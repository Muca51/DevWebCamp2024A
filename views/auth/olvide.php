<main class="auth">

    <h2 class="auth__heading"><?php echo $titulo; ?></h2>
    <p class="auth__text">Recupera tu Acceso a DevWebCamp</p>

    <?php
        require_once __DIR__ . '/../templates/alertas.php';
    ?>


    <form method="POST" class="formulario" action="/olvide">
        <div class="formulario__campo">
            <label class="formulario__label" for="email">Email</label>
            <input 
                type="email"
                class="formulario__input"
                placeholder="Tu email"
                id="email"
                name="email"
            />
        </div>

        <input type="submit" class="formulario__submit" value="Enviar Instrucciones">

    </form>

    <div class="acciones">
        <a href="/login" class="acciones__enlace">¿Ya tienes cuenta? Inicia Sesión</a>
        <a href="/registro" class="acciones__enlace">¿Aún no tienes cuenta? Obtener una</a>
    </div>

</main>