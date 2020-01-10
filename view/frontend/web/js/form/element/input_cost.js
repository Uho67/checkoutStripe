define([
           "jquery",
           "Magento_Ui/js/form/element/select",
           'uiRegistry'
       ], function ($, select, registry) {
    var area = registry.get('form_for_new_posta.form_for_new_posta.general.area'),
        city = registry.get('form_for_new_posta.form_for_new_posta.general.city'),
        warehouse = registry.get('form_for_new_posta.form_for_new_posta.general.warehouse_ref'),
        street = registry.get('form_for_new_posta.form_for_new_posta.general.street'),
        cost = registry.get('form_for_new_posta.form_for_new_posta.general.cost'),
        streets = [], cityRecipient;
    function changeWarehouse(newCity) {
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
                   xhrFields: {
                       withCredentials: false
                   },
                   success: function (response) {
                       let warehouses = [];
                       for (let i = 0; i < response.data.length; i++) {
                           warehouses.push({
                                               'value': response.data[i]['Ref'],
                                               'label': response.data[i]['DescriptionRu']
                                           })
                           streets[response.data[i]['Ref']] = response.data[i]['DescriptionRu'];
                       }
                       warehouse.setOptions(warehouses);
                       // area.disabled(true);
                   }
               });
    }
    function changeCity(newArea){
        let areaData = {};
        areaData.areaRef = newArea;
        $.ajax(
            {
                type: 'POST',
                url: 'http://devbox.vaimo.test/magento2/mytest_checkout/newpost/getcitybyarea',
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
    }
    area.value.subscribe(function (newArea) {
       changeCity(newArea)
    });
    city.value.subscribe(function (newCity) {
        cityRecipient = newCity;
        changeWarehouse(newCity)
    });
    warehouse.value.subscribe(function (newWarehouse) {
        street.value(streets[newWarehouse]);
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
                                                "ServiceType": "WarehouseWarehouse",
                                                "Cost": "100",
                                                "CargoType": "Cargo",
                                                "SeatsAmount": "1",
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
                       cost.value(response.data[0]['Cost']);
                   }
               });
    });
    return select.extend({})
})