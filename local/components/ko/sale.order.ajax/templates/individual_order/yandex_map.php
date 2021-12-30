<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$APIKEY = 'f36227c3-d393-49a0-864d-e85a555d0945'; // из настроек файла init.php?>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=<?=$APIKEY?>" type="text/javascript"></script>
<script>
    
    ymaps.ready(init);

    // подключаем Яндекс карту для расчета маршрута
    function init() {

         console.log('init');

        // Стоимость за километр.
        let DELIVERY_TARIFF = BX.message('DELIVERY_TARIFF'), // берется из настроек кухни Битрикс
            DELIVERY_MAX_DISTANCE = BX.message('DELIVERY_MAX_DISTANCE'), // берется из настроек кухни Битрикс
            // Минимальная стоимость.
            DELIVERY_COST = BX.message('DELIVERY_COST'), // берется из настроек кухни Битрикс
            ADDRESS = BX.message('ADDRESS'),
            myMap = new ymaps.Map('map', {
                center: [54.615819, 39.695187], // берется из настроек кухни Битрикс Адрес
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
            // from: 'Рязань, Островского 128' // адрес кухни Битрикс Адрес
            from: ADDRESS // адрес кухни Битрикс Адрес
        });

        let address_street = document.querySelector('#soa-property-23');
        let address_home = document.querySelector('#soa-property-24');
        let address = 'Рязань, ' + address_street.value.trim() +' '+ address_home.value.trim();

        routePanelControl.routePanel.state.set({
            toEnabled: false,
            to: address
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
                    // console.log(length.value);
                    let price = 'доставка не возможна';

                    if(length.value <= DELIVERY_MAX_DISTANCE){
                        price = calculate(Math.round(length.value / 1000));
                        balloonContentLayout = '<span>Расстояние: ' + length.text + '.</span><br/>' +
                            '<span style="font-weight: bold; font-style: italic">Стоимость доставки: ' + price + ' р.</span>';
                    }else{
                        balloonContentLayout = '<span>Расстояние: ' + length.text + '. заказ на данное расстояние не возможен! </span>';
                    }

                    var delivery_price = document.getElementById('soa-property-34');
                        delivery_price.value = price;      

                    var delivery_cost = document.querySelector('.bx-soa-pp-delivery-cost');
                        delivery_cost.innerHTML = price        
                        // console.log(balloonContentLayout);
                }

            });

        });      

        // Функция, вычисляющая стоимость доставки.
        function calculate(routeLength) {
            return Math.max(routeLength * DELIVERY_TARIFF, DELIVERY_COST);
        }
    }
</script>
<div id="map" style="display: none;"></div>