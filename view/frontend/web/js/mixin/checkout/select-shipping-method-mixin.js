define([
   'jquery',
   'uiRegistry'
], function ($, registry) {
'use strict';

    return function (target) {
        return function (shippingMethod) {
            var areas = [],cities = [];
            var cityRecipient;
            var city = registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.new_post_city');
            var street_number = registry.get("checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.street.0");
            var street_street = registry.get("checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.street.1");
            var street_house = registry.get("checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.street.2");
            var standardCityField = registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.city');
            var area = registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.new_post_area');
            var warehouse = registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.new_post_warehouse');
            var region = registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.region_id_input');
            if(shippingMethod && shippingMethod.method_code == "vaimo_stripe_newpost") {
                city.visible(true);
                warehouse.visible(true);
                area.visible(true);
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "http://devbox.vaimo.test/magento2/rest/all/V1/getAreaList",
                    success: function (response) {
                        var region = [];
                        for (var i = 0; i < response.length; i++) {
                            region.push({
                                                'value': response[i]['area_ref'],
                                                'label': response[i]['area_name']
                                            });
                            areas[response[i]['area_ref']] = response[i]['area_name'];
                        }
                        area.setOptions(region);
                    }
                });
            }else{
                    area.visible(false);
                    city.visible(false);
                    warehouse.visible(false);
                    // standardCityField.visible(true);
             }

            area.value.subscribe(function (newarea) {
                region.value(areas[newarea]);
                var areaData = {};
                areaData.areaRef = newarea;
                $.ajax(
                    {
                        type: 'POST',
                        url: 'http://devbox.vaimo.test/magento2/mytest_checkout/newpost/getcitybyarea',
                        data: areaData,
                        dataType: 'json',
                        success: function (newData) {
                            city.setOptions(newData);
                            for(let i=0;i<newData.length;i++){
                                  cities[newData[i].value] = newData[i].label;
                            }
                        },
                        error: function (er) {
                            console.log(er);
                        }
                    }
                )
            });
            city.value.subscribe(function (newCity) {
                standardCityField.value(cities[newCity]);
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
                           xhrFields: {
                               withCredentials: false
                           },
                           success: function (response) {
                               var warehouses = [];
                               for (var i = 0; i < response.data.length; i++) {
                                   warehouses.push({
                                                       'value': response.data[i]['DescriptionRu'],
                                                       'label': response.data[i]['DescriptionRu']
                                                   })
                               }
                               warehouse.setOptions(warehouses);

                           }
                       });
            });
            warehouse.value.subscribe(function (newWarehouse) {
                       var address = newWarehouse.split(',');
                       street_house.value('home '+address[1]);
                       address = address[0].split(':');
                       street_street.value(address[1]);
                       street_number.value(address[0]);
            })

            target(shippingMethod);
        };
    }
});
