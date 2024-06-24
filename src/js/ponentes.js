(function () {
    const ponentesInput = document.querySelector('#ponentes');
    if (ponentesInput) {
        let ponentes = [];
        let ponentesFiltrados = [];

        const listadoPonentes = document.querySelector('#listado-ponentes')
        const ponenteHidden = document.querySelector('[name="ponente_id"]');

        obtenerPonentes();
        //? input: el evento input es cuando se escribe en el input
        ponentesInput.addEventListener('input', buscarPonentes)
        //! en caso de tener valor id del ponente
        if (ponenteHidden.value) {
            (async () => {
                const ponente = await obtenerPonente(ponenteHidden.value);
            
                const { nombre, apellido } = ponente
               
                //!insertar en el html
                const ponenteDOM = document.createElement('LI');
                ponenteDOM.classList.add('listado-ponentes__ponente', 'listado-ponentes__ponente--seleccionado');
                ponenteDOM.textContent = `${nombre} ${apellido}`;
                
                listadoPonentes.appendChild(ponenteDOM);
            })();
        }

        async function obtenerPonentes() {
            //   const { dia, categoria_id } = busqueda
            const url = `/api/ponentes`//?dia_id=${dia}&categoria_id=${categoria_id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();

            formatearPonentes(resultado);

        }
        async function obtenerPonente(id) {
            const url = `/api/ponente?id=${id}`;
            const respuesta = await fetch(url);//obtener respuesta a la url
            const resultado = await respuesta.json();//convertir la respuesta a json
            return resultado;
        }
        function formatearPonentes(arrayPonentes = []) {
            ponentes = arrayPonentes.map(ponente => {
                return {
                    //? el trim(): elimina espacio en blanco
                    nombre: `${ponente.nombre.trim()} ${ponente.apellido.trim()}`,
                    id: ponente.id
                }
            })
            console.log(ponentes);
        }
       
        function buscarPonentes(e) {
            const busqueda = e.target.value;
            if (busqueda.length > 2) {
                const expresion = new RegExp(busqueda, "i")//expresion regular: forma de buscar  un patron en un valor
                ponentesFiltrados = ponentes.filter(ponente => {
                    if (ponente.nombre.toLowerCase().search(expresion) != -1) {
                        return ponente
                    }
                })
            } else {
                ponentesFiltrados = [];//cuando no se tiene 3 letras tener un arreglo vacio
            }
            mostrarPonentes()
        }
        function mostrarPonentes() {
            //!forma1 mala practica
            //listadoPonentes.innerHTML = '';//limpiar el listado ponentes

            //!forma 2 mas performance
            //todo: mientras haya ponentes en listado ponentes, removerlos
            while (listadoPonentes.firstChild) {
                listadoPonentes.removeChild(listadoPonentes.firstChild);
            }

            if (ponentesFiltrados.length > 0) {//si hay ponentes
                ponentesFiltrados.forEach(ponente => {
                    const ponenteHtml = document.createElement('LI');
                    ponenteHtml.classList.add('listado-ponentes__ponente');
                    ponenteHtml.textContent = ponente.nombre;
                    ponenteHtml.dataset.ponenteId = ponente.id;
                    ponenteHtml.onclick = seleccionarPonente

                    //añadir al dom
                    listadoPonentes.appendChild(ponenteHtml);
                })
            }
            else {// si no hay ponentes
                const noResultados = document.createElement('P');
                noResultados.classList.add('listado-ponentes__no-resultado');
                noResultados.textContent = 'No Hay Resultados para tu busqueda';
                listadoPonentes.appendChild(noResultados);
            }
        }
        function seleccionarPonente(e) {

            const ponente = e.target;

            //! remover la clase previa
            const ponentePrevio = document.querySelector('.listado-ponentes__ponente--seleccionado');
            if (ponentePrevio) {
                ponentePrevio.classList.remove('listado-ponentes__ponente--seleccionado');
            }
            ponente.classList.add('listado-ponentes__ponente--seleccionado');
            console.log(ponente);

            ponenteHidden.value = ponente.dataset.ponenteId;

        }
    }
})();