<main class="registro">
    <h2 class="registro__heading"><?php echo $titulo; ?></h2>
    <p class="registro__descripcion">Elige tu plan</p>

    <div class="paquetes__grid">
        <div class="paquete">
            <h3 class="paquete__nombre">Pase Gratis</h3>
            <ul class="paquete__lista">
                <li class="paquete__elemento">Acceso Virtual a DevWebCamp</li>
            </ul>
            <p class="paquete__precio">$0</p>
            <form method="POST" action="/finalizar-registro/gratis">
                <input type="submit" value="Inscripción Gratis" class="paquetes__submit">
            </form>
        </div>
        <div class="paquete">
            <h3 class="paquete__nombre">Pase Presencial</h3>
            <ul class="paquete__lista">
                <li class="paquete__elemento">Acceso Presencial a DevWebCamp</li>
                <li class="paquete__elemento">Pase por dos dias</li>
                <li class="paquete__elemento">Acceso a talleres y conferencias</li>
                <li class="paquete__elemento">Acceso a las grabaciones</li>
                <li class="paquete__elemento">Camisa del evento</li>
                <li class="paquete__elemento">Comida y bebida</li>
            </ul>
            <p class="paquete__precio">$199</p>
            <!-- <div id="paypal-container-R5BMHSRS29HAN"></div> -->

            <div id="paypal-container-presencial"></div>
            
        </div>
        <div class="paquete">
            <h3 class="paquete__nombre">Pase Virtual</h3>
            <ul class="paquete__lista">
                <li class="paquete__elemento">Acceso Virtual a DevWebCamp</li>
                <li class="paquete__elemento">Pase por 2 dias</li>
                <li class="paquete__elemento">Acceso a talleres y conferencias</li>
                <li class="paquete__elemento">Acceso a las grabaciones</li>
            </ul>
            <p class="paquete__precio">$49</p>
            <div id="paypal-container-virtual"></div>
        </div>
    </div>



</main>
<script src="https://www.paypal.com/sdk/js?client-id=AYo5IxyZa1AfuCi3niDi_8J2GIQUDFE22aieRepNQ2PcJxQbdJ_HxQDn12m5hl09hjNDbqp8K1feZ1AG&currency=MXN"></script>

<script>
    function initPayPalButton() {
        //! pase presencial
        paypal.Buttons({
            style: {
                layout: 'vertical',
                color: 'blue',
                shape: 'pill',
                label: 'pay',
            },
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        "description": "1",
                        "amount": {
                            "currency_code": "MXN",
                            "value": 199
                        }
                    }]
                });
            },


            onApprove: function(data, actions) {
                return actions.order.capture().then(function(orderData) {

                      //console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));


                    const datos = new FormData();
                    datos.append('paquete_id', orderData.purchase_units[0].description);
                    datos.append('pago_id',orderData.purchase_units[0].payments.captures[0].id);
                    //console.log(datos);

                    fetch('/finalizar-registro/pagar', {
                            method: 'POST',
                            body: datos
                        }).then(respuesta => respuesta.json())
                        .then(resultado => {
                            //resultado(js).resultado(php)
                            if (resultado.resultado) {
                                actions.redirect('http://localhost:3000/finalizar-registro/conferencias');
                            }
                        })

                        
                });
            },


            onError: function(err) {
                console.log(err);
                alert('kevin tenemos el error : '.err);
            }
        }).render("#paypal-container-presencial")
        


        //! pase virtual
        paypal.Buttons({
            style: {
                layout: 'vertical',
                color: 'blue',
                shape: 'pill',
                label: 'pay',
            },
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        "description": "2",
                        "amount": {
                            "currency_code": "MXN",
                            "value": 49
                        }
                    }]
                });
            },


            onApprove: function(data, actions) {
                return actions.order.capture().then(function(orderData) {

                      //console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));


                    const datos = new FormData();
                    datos.append('paquete_id', orderData.purchase_units[0].description);
                    datos.append('pago_id',orderData.purchase_units[0].payments.captures[0].id);
                    //console.log(datos);

                    fetch('/finalizar-registro/pagar', {
                            method: 'POST',
                            body: datos
                        }).then(respuesta => respuesta.json())
                        .then(resultado => {
                            //resultado(js).resultado(php)
                            if (resultado.resultado) {
                                actions.redirect('http://localhost:3000/finalizar-registro/conferencias');
                            }
                        })

                        
                });
            },


            onError: function(err) {
                console.log(err);
                alert('kevin tenemos el error : '.err);
            }
        }).render("#paypal-container-virtual")

    }
    initPayPalButton();
</script>