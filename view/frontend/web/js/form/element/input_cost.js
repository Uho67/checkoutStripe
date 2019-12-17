define([
           "jquery",
           "Magento_Ui/js/form/element/select",
           'uiRegistry'
       ], function ($, select, registry) {
    var area = registry.get('form_for_new_posta.form_for_new_posta.general.area');
    var city = registry.get('form_for_new_posta.form_for_new_posta.general.city');
    var warehouse = registry.get('form_for_new_posta.form_for_new_posta.general.warehouse');
    var cityRecipient;
    area.value.subscribe(function (newarea) {
        var areaData = {};
        areaData.areaRef = newarea;
        $.ajax(
            {
                type: 'POST',
                url: 'http://devbox.vaimo.test/newmagento/mytest_checkout/newpost/getcitybyarea',
                data: areaData,
                dataType: 'json',
                success: function (newData) {
                    city.setOptions(newData);
                },
                error: function (er) {
                    console.log(er);
                }
            }
        )
    });
    city.value.subscribe(function (newCity) {
        cityRecipient = newCity;
        $.ajax({
                   type: "POST",
                   dataType: "json",
                   url: "https://api.novaposhta.ua/v2.0/json/",
                   data: JSON.stringify({
                                            modelName: "AddressGeneral",
                                            calledMethod: "getWarehouses",
                                            methodProperties: {
                                                "CityRef": newCity,
                                                Limit: 555
                                            },
                                            apiKey: "f5b54b3f7ce5800ca0ffcd95a4dbed15"
                                        }),
                   headers: {
                       "Content-Type": "application/json"
                   },
                   xhrFields: { // Свойство 'xhrFields' устанавливает дополнительные поля в XMLHttpRequest. // Это можно использовать для установки свойства 'withCredentials'. // Установите значение «true», если вы хотите передать файлы cookie на сервер. // Если это включено, ваш сервер должен ответить заголовком // 'Access-Control-Allow-Credentials: true'.
                       withCredentials: false
                   },
                   success: function (response) {
                       console.log(response.data[0]['Ref']);
                       var warehauses = [];
                       for (var i = 0; i < response.data.length; i++) {
                           warehauses.push({
                                               'value': response.data[i]['Ref'],
                                               'label': response.data[i]['Number']
                                           })
                       }
                       warehouse.setOptions(warehauses);
                   }
               });
    });
    warehouse.value.subscribe(function (newWarehouse) {

        $.ajax({
                   type: "POST",
                   dataType: "json",
                   url: "https://api.novaposhta.ua/v2.0/json/",
                   data: JSON.stringify({
                                            modelName: "InternetDocument",
                                            calledMethod: "getDocumentPrice",
                                            methodProperties: {
                                                "CitySender": "db5c88e0-391c-11dd-90d9-001a92567626",
                                                "CityRecipient": cityRecipient,
                                                "Weight": "10",
                                                "ServiceType": "DoorsDoors",
                                                "Cost": "100",
                                                "CargoType": "Cargo",
                                                "SeatsAmount": "10",
                                                "PackCount": "1",
                                                "PackRef": "1499fa4a-d26e-11e1-95e4-0026b97ed48a"
                                            },
                                            apiKey: "f5b54b3f7ce5800ca0ffcd95a4dbed15"
                                        }),
                   headers: {
                       "Content-Type": "application/json"
                   },
                   xhrFields: {
                       withCredentials: false
                   },
                   success: function (response) {
                       console.log(response.data[0]['Cost']);
                   }
               });
    })


    return select.extend({})
})