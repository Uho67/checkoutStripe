define([
   'jquery',
   'uiRegistry',
   'Mytest_Checkout/js/view/newpost/get-address'
], function ($, registry,address) {
'use strict';
    return function (target) {
        return function (shippingMethod) {
            function changeFields(regions) {
                var warehouses = [];
                regionId.setOptions(regions);
                regionId.value.subscribe(function (region_id) {
                    let areaData = {};
                    for(let i= 0;i<areas.length;i++){
                        if(areas[i]['entity_id'] === region_id){
                            areaData.areaRef = areas[i]['area_ref'];
                        }
                    }
                    $.ajax(
                        {
                            type: 'POST',
                            url: 'http://devbox.vaimo.test/magento2/mytest_checkout/newpost/getcitybyarea',
                            data: areaData,
                            dataType: 'json',
                            success: function (newData) {
                                city.setOptions(newData);
                                for (let i=0;i<newData.length;i++){
                                    cities[newData[i].value] = newData[i].label;
                                }
                                        standardCityField.value(cities[city.value._latestValue]);

                            },
                            error: function (er) {
                                console.log(er);
                            }
                        }
                    )
                });
                city.value.subscribe(function (newCity) {
                    standardCityField.value(cities[newCity]);
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
                                   let labelWarehouses = [];
                                   for (let i = 0; i < response.data.length; i++) {
                                       labelWarehouses.push({
                                                                'value': response.data[i]['Ref'],
                                                                'label': response.data[i]['DescriptionRu']
                                                            })
                                       warehouses[response.data[i]['Ref']] = response.data[i]['DescriptionRu'];
                                   }
                                   warehouse.setOptions(labelWarehouses);
                               }
                           });
                });
                warehouse.value.subscribe(function (newWarehouse) {
                    newWarehouse = warehouses[newWarehouse];
                    var address = newWarehouse.split(',');
                    street_house.value('home '+address[1]);
                    address = address[0].split(':');
                    street_street.value(address[1]);
                    street_number.value(address[0]);
                })
            }
            function arrEqualRegion(arr1,arr2){
                for(let i=0;i<arr2.length;i++){
                    if(arr1[i+1]) {
                        if (arr1[i+1].value != arr2[i].value || arr1[i+1].label != arr2[i].label) return false;
                    }else {
                        return false;
                    }
                }
                return true;
            }
            var areas = [],cities=[];
            var city = registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.new_post_city');
            var street_number = registry.get("checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.street.0");
            var street_street = registry.get("checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.street.1");
            var street_house = registry.get("checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.street.2");
            var standardCityField = registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.city');
            var warehouse = registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.new_post_warehouse');
            var regionId = registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.region_id');
            if(shippingMethod && shippingMethod.method_code == "vaimo_stripe_newpost") {
                city.visible(true);
                warehouse.visible(true);
                standardCityField.visible(false);
                $.ajax({
                           type: "GET",
                           dataType: "json",
                           url: "http://devbox.vaimo.test/magento2/rest/all/V1/getAreaList",
                           success: function (response) {
                               let regions =[];
                               for (let i = 0; i < response.length; i++) {
                                   regions.push({
                                                   'value': response[i]['entity_id'],
                                                   'label': response[i]['area_name']
                                               });
                               }
                               areas = response;

                               if(!arrEqualRegion(regionId.indexedOptions,regions)) {
                                   changeFields(regions)
                               }
                           },
                           error: function (er) {
                               console.log(er);
                               return false;
                           }
                       })
            }else{
                    city.visible(false);
                    warehouse.visible(false);
                    standardCityField.visible(true);
             }
            target(shippingMethod);
        };
    }
});
