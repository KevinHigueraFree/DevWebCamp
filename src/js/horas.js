(function () {
    const horas = document.querySelector('#horas');

    if (horas) {

        const categoria = document.querySelector('[name="categoria_id"]')
        const dias = document.querySelectorAll('[name="dia"]');
        const inputHiddenDia = document.querySelector('[name="dia_id"]');
        const inputHiddenHora = document.querySelector('[name="hora_id"]');

        categoria.addEventListener('change', terminoBusqueda)
        dias.forEach(dia => dia.addEventListener('change', terminoBusqueda))

        //todo: es un objeto de busqueda
        //? +categoria: hace el numero de string a entero
        let busqueda = {
            categoria_id: +categoria.value || '',
            dia: +inputHiddenDia.value || ''
        }
        //todo cuando el objeto busqueda  tiene un valor que no esté vacío 
        if (!Object.values(busqueda).includes('')) {//accede al valor del objeto busqueda  y verificar si incluye un string vacio

            //todo: hacemos la una funcion asincrona para que se ejecute antes de las siguientes lineas
            //?asyn: evita que se ejecuten ls demas lineas hasta que se ejecute la funcion
            /*
                      //!forma 1
                      async function iniciarApp() {
                          await buscarEventos();
                          //! resaltar hora actual
                          const id = inputHiddenHora.value;
                          const horaSeleccionada = document.querySelector(`[data-hora-id="${id}"]`);
                          horaSeleccionada.classList.remove('horas__hora--deshabilitada');
                          horaSeleccionada.classList.add('horas__hora--seleccionada');
                          console.log('es una funcion');
                      }
                      iniciarApp();
          */
            //! forma 2
            (async () => {
                await buscarEventos();
                //! resaltar hora actual
                const id = inputHiddenHora.value;
                const horaSeleccionada = document.querySelector(`[data-hora-id="${id}"]`);
                horaSeleccionada.classList.remove('horas__hora--deshabilitada');
                horaSeleccionada.classList.add('horas__hora--seleccionada');
                horaSeleccionada.onclick = seleccionarHora;//para seleccionar el id de la hora
            })();
        }

        function terminoBusqueda(e) {

            //inputHiddenDia.value=e.target.value//asigna el valor(id) al value de el inputhidden
            busqueda[e.target.name] = e.target.value;

            //!reiniciar los campos ocultos y selector de horas y dia
            inputHiddenHora.value = '';//limpiamos
            inputHiddenDia.value = '';//limpiamos


            const horaPrevia = document.querySelector('.horas__hora--seleccionada');
            if (horaPrevia) {
                horaPrevia.classList.remove('horas__hora--seleccionada');
            }
            //  console.log(busqueda);
            /* if(e.target.checked){
                  horas.disabled=false;
              }else{
                  horas.disabled=true;
              }
              */
            //console.log(Object.values(busqueda));;
            if (Object.values(busqueda).includes('')) {//accede al valor del objeto busqueda  y verificar si incluye un string vacio
                return;
            }
            buscarEventos();
        }
        async function buscarEventos() {
            const { dia, categoria_id } = busqueda
            const url = `/api/eventos-horario?dia_id=${dia}&categoria_id=${categoria_id}`;
            const resultado = await fetch(url);
            const eventos = await resultado.json();

            obtenerHorasDisponibles(eventos);

        }
        function obtenerHorasDisponibles(eventos) {
            //! reiniciar horas
            const listadoHoras = document.querySelectorAll('#horas li');
            listadoHoras.forEach(li => li.classList.add('horas__hora--deshabilitada'));

            //!comprobar eventos tomados y quitar variable de deshabilitada
            const horasTomadas = eventos.map(evento => evento.hora_id);
            const listadoHorasArray = Array.from(listadoHoras);

            const resultado = listadoHorasArray.filter(li => !horasTomadas.includes(li.dataset.horaId));
            resultado.forEach(li => li.classList.remove('horas__hora--deshabilitada'));

            const horasDisponibles = document.querySelectorAll('#horas li:not(.horas__hora--deshabilitada)');//traer todas las horas que no tengan la clase dehabilitada
            horasDisponibles.forEach(hora => hora.addEventListener('click', seleccionarHora));

            // console.log(horasDisponibles);
        }
        function seleccionarHora(e) {

            //!deshabilitar hora previa
            const horaPrevia = document.querySelector('.horas__hora--seleccionada');
            if (horaPrevia) {
                horaPrevia.classList.remove('horas__hora--seleccionada');
            }
            e.target.classList.add('horas__hora--seleccionada');
            inputHiddenHora.value = e.target.dataset.horaId;


            //!llenar el input oculto de dia
            inputHiddenDia.value = document.querySelector('[name="dia"]:checked').value;

        }
    }
})();