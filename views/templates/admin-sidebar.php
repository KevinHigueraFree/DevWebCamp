<aside class="dashboard__sidebar">
    <nav class="dashboard__menu">
        <!-- Aqui comprobamos si la url que pasamos es la misma de el navegador y si si, ponemos la clase actual para que se realste el enlace perteneciente a la seccion seleccionada -->
        <a href="/admin/dashboard" class="dashboard__enlace <?php echo pagina_actual('/dashboard') ? 'dashboard__enlace--actual': ''; ?>">
            <i class="fa-solid fa-house dashboard__icono"></i>
            <span class="dashboard__menu-texto">Inicio</span>
        </a>
        <a href="/admin/ponentes"  class="dashboard__enlace <?php echo pagina_actual('/ponentes') ? 'dashboard__enlace--actual': ''; ?>">
            <i class="fa-solid fa-microphone dashboard__icono"></i>
            <span class="dashboard__menu-texto">Ponentes</span>
        </a>
        <a href="/admin/eventos"  class="dashboard__enlace <?php echo pagina_actual('/eventos') ? 'dashboard__enlace--actual': ''; ?>">
            <i class="fa-solid fa-calendar dashboard__icono"></i>
            <span class="dashboard__menu-texto">Eventos</span>
        </a>
        <a href="/admin/registrados"  class="dashboard__enlace <?php echo pagina_actual('/registrados') ? 'dashboard__enlace--actual': ''; ?>">
            <i class="fa-solid fa-users dashboard__icono"></i>
            <span class="dashboard__menu-texto">Registrados</span>
        </a>
        <a href="/admin/regalos"  class="dashboard__enlace <?php echo pagina_actual('/regalos') ? 'dashboard__enlace--actual': ''; ?>">
            <i class="fa-solid fa-gift dashboard__icono"></i>
            <span class="dashboard__menu-texto">Regalos</span>
        </a>
    </nav>
</aside>