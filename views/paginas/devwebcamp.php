<main class="devwebcamp">
    <h2 class="devwebcamp__heading"><?php echo $titulo; ?></h2>
    <p class="devwebcamp__descripcion">Conoce la conferencia mas importante en Latinoamerica</p>

    <div class="devwebcamp__grid">
        <div <?php  aos_animacion(); ?> class="devwebcamp-imagen">
            <picture>
                <source srcset="build/img/sobre_devwebcamp.avif" type="image/avif">
                <source srcset="build/img/sobre_devwebcamp.webp" type="image/webp">
                <img src="build/img/sobre_devwebcamp.jpg" loading="lazy" width="280<" height="300" alt="imagen devwebcamp">
            </picture>
        </div>
        <div  class="devwebcamp__contenido">
            <p <?php  aos_animacion(); ?> class="devwebcamp__texto">Si eres una persona con habilidades de desarrollo web y buscas reforzar tu conocimiento estás en el lugar indicado. Nosotros contamos con las personas indicadas quienes seran los anfitriones de cada evento.</p>
            <p <?php  aos_animacion(); ?> class="devwebcamp__texto">Aquí encontrarás diversos temas de interés, contamos con las categorías de Conferencias y la de Workshops. A su vez puedes asistir de manera virtual o presencial, lo que mejor se acomode a tus posibilidades.  Tenemos temas muy diversos para que puedas elegir los que mejor coincidan con tu especialización. ¡Te esperamos!.</p>
        </div>

    </div>
</main>