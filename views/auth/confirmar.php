<main class="auth">
    <h2 class="auth__heading"><?php echo $titulo; ?></h2>
    <p class="auth__texto">Tu cuenta DebWebCamp</p>

    <?php
    require_once __DIR__ . '/../templates/alertas.php';
    ?>

<?php
//si existe alerta exito hacer...
if (isset($alertas['exito'])){
?>
<div class="acciones--centrar">
        <a href="/login" class="acciones__enlace">Iniciar Sesión</a>
    </div>
    <?php }?>
</main>