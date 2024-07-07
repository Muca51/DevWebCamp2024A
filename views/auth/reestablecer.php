<main class="auth">

    <?php
        if($token_valido) { ?>

            <h2 class="auth__heading"><?php echo $titulo; ?></h2>
                <p class="auth__text">Coloca tu nuevo password</p>

                <?php
                    require_once __DIR__ . '/../templates/alertas.php';
                ?>          

                <form method="POST" class="formulario">
                
                    <div class="formulario__campo">
                        <label class="formulario__label" for="password">Password Nuevo</label>
                        <input 
                            type="password"
                            class="formulario__input"
                            placeholder="Tu password"
                            id="password"
                            name="password"
                        />
                    </div>

                    <div class="formulario__campo">
                        <label class="formulario__label" for="password2">Confirma tu Password Nuevo</label>
                        <input  
                            type="password"
                            class="formulario__input"
                            placeholder="Repite tu password"
                            id="password2"
                            name="password2"
                        />
                    </div>

                    <input type="submit" class="formulario__submit" value="Guardar Password">

                </form>

                <div class="acciones">
                    <a href="/login" class="acciones__enlace">¿Ya tienes una cuenta? Inicia Sesión</a>
                    <a href="/olvide" class="acciones__enlace">¿Olvidaste tu password?</a>
                </div>
        <?php } else { ?>
            <h1>El token no es válido revise</h1>
            <a href="/olvide" class="acciones__enlace">¿Olvidaste tu password?</a>
    <?php } ?>
        
</main>