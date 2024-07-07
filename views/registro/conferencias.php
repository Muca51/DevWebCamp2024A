<h2 class="pagina__heading"><?php echo $titulo; ?></h2>
<p class="pagina__descripcion"> Elige hasta 5 eventos para asistir de forma presencial</p>

    <div class="eventos-registro">
        <main class="eventos-registro__listado">
            <h3 class="eventos-registro__heading--conferencias">&lt;Conferencias /></h3>

            <p class="eventos-registro__fecha">Viernes 28 de Junio</p>

            <div class="eventos-registro__grid">
                <?php if (isset($eventos['conferencias_v']) && is_array($eventos['conferencias_v'])) { ?>
                    <?php foreach($eventos['conferencias_v'] as $evento) { ?>
                        <?php include __DIR__ . '/evento.php'; ?>
                    <?php } ?>
                <?php } else { ?>
                    <p>No hay conferencias disponibles.</p>
                <?php } ?>
            </div>

            <p class="eventos-registro__fecha">Sábado 29 de Junio</p>

            <div class="eventos-registro__grid">
                <?php if (isset($eventos['conferencias_s']) && is_array($eventos['conferencias_s'])) { ?>
                    <?php foreach($eventos['conferencias_s'] as $evento) { ?>
                        <?php include __DIR__ . '/evento.php'; ?>
                    <?php } ?>
                <?php } else { ?>
                    <p>No hay conferencias disponibles.</p>
                <?php } ?>
            </div>


            <h3 class="eventos-registro__heading--workshops">&lt;WorkShops /></h3>

            <p class="eventos-registro__fecha">Viernes 28 de Junio</p>

            <div class="eventos-registro__grid eventos--workshops">
                <?php if (isset($eventos['workshops_v']) && is_array($eventos['workshops_v'])) { ?>
                    <?php foreach($eventos['workshops_v'] as $evento) { ?>
                        <?php include __DIR__ . '/evento.php'; ?>
                    <?php } ?>
                <?php } else { ?>
                    <p>No hay workshops disponibles.</p>
                <?php } ?>
            </div>

            <p class="eventos-registro__fecha">Sábado 29 de Junio</p>

            <div class="eventos-registro__grid eventos--workshops">
                <?php if (isset($eventos['workshops_s']) && is_array($eventos['workshops_s'])) { ?>
                    <?php foreach($eventos['workshops_s'] as $evento) { ?>
                        <?php include __DIR__ . '/evento.php'; ?>
                    <?php } ?>
                <?php } else { ?>
                    <p>No hay workshops disponibles.</p>
                <?php } ?>
            </div>

        </main>

        <aside class="registro">
            <h2 class="registro__heading">Tu Registro</h2>

            <div id="registro-resumen" class="registro__resumen"></div>

            <div class="registro__regalo">
                <label for="regalo" class="registro__label">Selecciona un regalo</label>
                <select id="regalo" class="registro__select">
                    <option value="">-- Selecciona tu regalo --</option>
                    <?php foreach($regalos as $regalo) { ?>
                        <option value="<?php echo $regalo->id; ?>"><?php echo $regalo->nombre; ?></option>

                    <?php } ?>
                </select>
            </div>

            <form id="registro" class="formulario">
                <div class="formulario__campo">
                    <input type="submit" class="formulario__submit formulario__submit--full" value="Registrarme">
                </div>
            </form>

        </aside>
    </div>


