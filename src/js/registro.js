import Swal from 'sweetalert2'

(function () {

    let eventos = [];
    const resumen = document.querySelector('#registro__resumen');

    if (resumen) {


        const eventosBoton = document.querySelectorAll('.evento__agregar');
        eventosBoton.forEach(boton => boton.addEventListener('click', seleccionarEvento));

        const formularioRegistro = document.querySelector('#registro');
        formularioRegistro.addEventListener('submit', submitFormulario);

        mostrarEventos();

        function seleccionarEvento(e) {
            if (eventos.length < 5) {

                //! Deshabilitar el evento
                const { target } = e;
                target.disabled = true; //se extrae el objeto target de el evento e 
                //todo se hace una copia de eventos con ...eventos y se le agregamos el objeto con el id y el titulo
                eventos = [...eventos, {
                    id: target.dataset.id,
                    titulo: target.parentElement.querySelector('.evento__nombre').textContent.trim() //todo parentElemet hace referencia al elemento que este en la misma gerarquía de el evento(la clase .evento__agregar) en este caso
                }]
                mostrarEventos();
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Solo puedes elegir 5 eventos',
                    icon: 'error',
                    confirmButtonText: 'Entiendo'

                })
            }




        }

        function mostrarEventos() {
            //!limpiar el html
            limpiarEventos();


            if (eventos.length > 0) {
                eventos.forEach(evento => {

                    const eventoDOM = document.createElement('div')
                    eventoDOM.classList.add('registro__evento');

                    const titulo = document.createElement('H3');
                    titulo.classList.add('registro__nombre');
                    titulo.textContent = evento.titulo;

                    //!boton de eliminar
                    const botonEliminar = document.createElement('BUTTON');
                    botonEliminar.classList.add('registro__eliminar');
                    botonEliminar.innerHTML = `<i class="fa-solid fa-trash"></i>`;
                    botonEliminar.onclick = function () {
                        eliminarEvento(evento.id)
                    }


                    //! renderizar en el html
                    eventoDOM.appendChild(titulo);//agregamos el titulo al evento dom
                    eventoDOM.appendChild(botonEliminar);//agregamos el boton al evento dom
                    resumen.appendChild(eventoDOM);//agregamos el evento dom al resumen


                })
            } else {
                const noRegistro = document.createElement('P');
                noRegistro.textContent = 'No hay eventos, puedes añadir hasta 5 del lado izquierdo'
                noRegistro.classList.add('registro__texto');
                resumen.appendChild(noRegistro);
            }

        }
        function eliminarEvento(id) {
            //todo sera igual a evento (quien retorna un arreglo) y se creara la variable evento para acceder al evento.id

            eventos = eventos.filter(evento => evento.id !== id);//todo se trae todos lo evento diferentes al evento al cual se le da click
            //!forma Juan
            const botonAgregar = document.querySelector(`[data-id="${id}"]`);
            botonAgregar.disabled = false
            mostrarEventos();
            //deseleccionarEvento(id); forma kevin
        }
        //! forma Kevin
        /*  function deseleccionarEvento(id) {
            eventos.forEach(evento => {
                if (evento.id === id) {
                    console.log(evento.id);
                      eventosBoton.forEach(boton => {
                          if (boton.dataset.id === evento.id) {
                              boton.disabled = false;
                          }
                        }
                    )
                }
            })
            
        }
        */

        function limpiarEventos() {
            while (resumen.firstChild) {
                resumen.removeChild(resumen.firstChild);
            }
        }
        async function submitFormulario(e) {
            e.preventDefault();//se previene la accion que es el de leer el valor de action  y el method
            //Obtener el regalo
            const regaloId = document.querySelector('#regalo').value;// con el .value accedemos al valor
            //todo con el map accedemos y extraemos los lo datos que le decimos en este caso evento.id
            const eventosId = eventos.map(evento => evento.id);

            if (eventosId === 0 || regaloId === '') {
                Swal.fire({
                    title: 'Error',
                    text: 'Elige al menos 1 Evento y 1 Regalo',
                    icon: 'error',
                    confirmButtonText: 'Entiendo'
                })
                return;
            }
            //Objeto de formData
            const datos = new FormData();
            datos.append('eventos', eventosId);
            datos.append('regalo_id', regaloId);

            const url = '/finalizar-registro/conferencias'
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            })
            const resultado = await respuesta.json();
            console.log(resultado);
            if (resultado.resultado) {
                Swal.fire({
                    title: 'Registro exitoso',
                    text: 'Tus conferencias se han almacenado y tu registro fue exitoso, nos vemos en DevWebCamp',
                    icon: 'success',
                    confirmButtonText: 'Entiendo'
                    //todo se redirecciona a otra pagina con su token del boleto despues de orpinir el boton de Entiendo
                }).then(() => location.href=`/boleto?id=${resultado.token}`)
            }
            else {
                Swal.fire({
                    title: 'Error',
                    text:'Hubo un error, intenta de nuevo',
                    icon: 'error',
                    confirmButtonText: 'Entiendo'
                }).then(() =>location.reload());
            }
        }

    }
})();