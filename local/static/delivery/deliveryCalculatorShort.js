// ymaps.ready(init);

function init() {
    // Стоимость за километр.
    let DELIVERY_TARIFF = 100, // берется из настроек кухни Битрикс
        DELIVERY_MAX_DISTANCE = 10000; // берется из настроек кухни Битрикс
        // Минимальная стоимость.
        MINIMUM_COST = 500, // берется из настроек кухни Битрикс
        myMap = new ymaps.Map('map', {
            center: [54.615819, 39.695187], // берется из настроек кухни Битрикс
            zoom: 12,
            controls: []
        }),
        // Создадим панель маршрутизации.
        routePanelControl = new ymaps.control.RoutePanel({
            options: {
                // Добавим заголовок панели.
                showHeader: true,
                title: 'Расчёт доставки'
            }
        }),
        zoomControl = new ymaps.control.ZoomControl({
            options: {
                size: 'small',
                float: 'none',
                position: {
                    bottom: 145,
                    right: 10
                }
            }
    });

    // Пользователь сможет построить только автомобильный маршрут.
    routePanelControl.routePanel.options.set({
        types: {auto: true}
    });

    // Если вы хотите задать неизменяемую точку "откуда", раскомментируйте код ниже.
    routePanelControl.routePanel.state.set({
        fromEnabled: false,
        from: 'Рязань, Островского 128' // адрес кухни Битрикс
    });
    let property = document.querySelector('#soa-property-24');
    routePanelControl.routePanel.state.set({
        toEnabled: false,
        to: property.value
    });

    myMap.controls.add(routePanelControl).add(zoomControl);

    // Получим ссылку на маршрут.
    routePanelControl.routePanel.getRouteAsync().then(function (route) {

        // Зададим максимально допустимое число маршрутов, возвращаемых мультимаршрутизатором.
        route.model.setParams({results: 1}, true);

        // Повесим обработчик на событие построения маршрута.
        route.model.events.add('requestsuccess', function () {
            let activeRoute = route.getActiveRoute();
            
            if (activeRoute) {
                // Получим протяженность маршрута.
                let length = route.getActiveRoute().properties.get("distance");  
                let balloonContentLayout = '';              
                console.log(length.value);

                if(length.value <= DELIVERY_MAX_DISTANCE){
                    let price = calculate(Math.round(length.value / 1000));
                    balloonContentLayout = '<span>Расстояние: ' + length.text + '.</span><br/>' +
                        '<span style="font-weight: bold; font-style: italic">Стоимость доставки: ' + price + ' р.</span>';
                }else{
                    balloonContentLayout = '<span>Расстояние: ' + length.text + '. заказ на данное расстояние не возможен! </span>';
                }
                // document.getElementById('result').innerHTML = balloonContentLayout;
                var deliveryMessage = document.getElementById("delivery_message");
                    if(deliveryMessage){
                       deliveryMessage.remove();
                    }
                    property.insertAdjacentHTML('afterEnd', '<span id="delivery_message">'+balloonContentLayout+'</span>')
                    console.log(balloonContentLayout);
            }

        });

    });

    // Функция, вычисляющая стоимость доставки.
    function calculate(routeLength) {
        return Math.max(routeLength * DELIVERY_TARIFF, MINIMUM_COST);
    }
}