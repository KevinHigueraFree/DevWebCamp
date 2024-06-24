(function () {
    const grafica = document.querySelector('#regalos-grafica');
    if (grafica) {
        obtenerDatos()
        async function obtenerDatos() {
            const url = '/api/regalos'
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();

            console.log(resultado);

            console.log('si hay grafica');

            const ctx = document.getElementById('regalos-grafica').getContext('2d');

            const myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: resultado.map(regalo => regalo.nombre),//se mapean los campo nombre del arreglo resultado
                    datasets: [{
                        label: '# of Votes',
                        data: resultado.map(regalo => regalo.total),// el crecimiento que tendra
                        backgroundColor: [
                            '#ea580c',
                            '#84cc16',
                            '#22d3ee',
                            '#a855f7',
                            '#ef4444',
                            '#14b8a6',
                            '#db2777',
                            '#e11d48',
                            '#7e22ce'

                        ]
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false//elimina el la lenda 
                        }
                    }
                }
            });
        }
    }

})();