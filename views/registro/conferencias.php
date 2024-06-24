<h2 class="pagina__heading"><?php echo $titulo; ?></h2>
<p class="pagina__descripcion">Elige hasta 5 eventos para asistir de forma presencial</p>
<div class="eventos-registro">

    <main class="eventos-registro__listado">
        <h2 class="eventos-registro__heading--conferencias">&lt;Conferencias /></h2>
        <p class="eventos-registro__descripcion">Viernes 3 de Mayo</p>
        <div class="eventos-registro__grid">
            <?php foreach ($eventos['conferencias_viernes'] as $evento) { ?>
                <?php include __DIR__ . '/evento.php'; ?>
            <?php } ?>
        </div>

        <p class="eventos-registro__descripcion">Sabado 4 de Mayo</p>
        <div class="eventos-registro__grid">
            <?php foreach ($eventos['conferencias_sabado'] as $evento) { ?>
                <?php include __DIR__ . '/evento.php'; ?>
            <?php } ?>
        </div>


        <h2 class="eventos-registro__heading--workshops">&lt;Workshops /></h2>
        <p class="eventos-registro__descripcion">Viernes 3 de Mayo</p>
        <div class="eventos-registro__grid eventos--workshops">
            <?php foreach ($eventos['workshops_viernes'] as $evento) { ?>
                <?php include __DIR__ . '/evento.php'; ?>
            <?php } ?>
        </div>

        <p class="eventos-registro__descripcion">Sabado 4 de Mayo</p>
        <div class="eventos-registro__grid  eventos--workshops">
            <?php foreach ($eventos['workshops_sabado'] as $evento) { ?>
                <?php include __DIR__ . '/evento.php'; ?>
            <?php } ?>
        </div>


    </main>


    <aside class="registro">
        <h2 class="registro__heading">Tu registro</h2>
        <div class="registro__resumen" id="registro__resumen"></div>


        <div class="registro__regalo">
            <label for="regalo" class="registro__label">Registro del regalo</label>
            <select id="regalo" class="registro__select">
                <option value="">--Selecciona tu regalo--</option>
                <?php foreach ($regalos as $regalo) { ?>
                    <option value="<?php echo $regalo->id; ?>"><?php echo $regalo->nombre; ?></option>

                <?php } ?>
            </select>
        </div>

        <form  id="registro" class="formulario">
            <div class="formulario__campo">
                <input type="submit" class="formulario__submit formulario__submit--full" value="Registrarme">
            </div>
        </form>

    </aside>
</div>