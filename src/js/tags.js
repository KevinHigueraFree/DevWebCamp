//! ifis se ejecuta por si solas y no afecta al resto del codigo
(function () {
    //alert('kevin');
    const tagsInput = document.querySelector('#tags_input');
    if (tagsInput) {
        const tagsDiv = document.querySelector('#tags');
        const tagsInputHidden = document.querySelector('[name="tags"]');
        let tags = [];
        //! recuperar del input oculto
        if (tagsInputHidden.value !== '') {
            tags = tagsInputHidden.value.split(',')
            mostrarTags(tags);
        }

        //escuchar los cambios ene l input
        tagsInput.addEventListener('keypress', guardarTag);

        function guardarTag(e) {
            //44 es el  codigo de la coma, key code es el codigo en numero de la letra
            if (e.keyCode === 44) {
                //! retornar si no tiene informacion
                if (e.target.value.trim() === '' || e.target.value < 1) {
                    return
                }
                e.preventDefault();//previene la accion por default de que el usuario escriba en el campo(la coma)

                tags = [...tags, e.target.value.trim()];//trim elimina espacios en blanco
                tagsInput.value = '';//vaciamos el input
                //  console.log(tags);

                mostrarTags();
            }
        }
        //! mostrar etiquetas
        function mostrarTags() {
            tagsDiv.textContent = '';
            tags.forEach(tag => {
                const etiqueta = document.createElement('LI');
                etiqueta.classList.add('formulario__tag');
                etiqueta.textContent = tag;
                etiqueta.ondblclick = eliminarTag;
                tagsDiv.appendChild(etiqueta);

            })
            actualizarInputHidden();
        }
        function actualizarInputHidden() {
            tagsInputHidden.value = tags.toString();//CONVIERTE EL ARREGLO DENTRO DE UN STRING
        }
        function eliminarTag(e) {
            e.target.remove(); //remover el tag del DOM(DE LA PANTALLA)
            tags = tags.filter(tag => tag !== e.target.textContent); //todo. se filtra o trae todos los tags que no se dio click
            actualizarInputHidden();
        }
    }
})();