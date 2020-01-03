define(
    [
        'jquery',
        'uiComponent'
    ], function ($, Component) {
        return Component.extend({
                                    getRegions: function () {
                                        $.ajax({
                                                   type: "GET",
                                                   dataType: "json",
                                                   url: "http://devbox.vaimo.test/magento2/rest/all/V1/getAreaList",
                                                   success: function (response) {
                                                       let region = [];
                                                       for (let i = 0; i < response.length; i++) {
                                                           region.push({
                                                                           'value': response[i]['entity_id'],
                                                                           'label': response[i]['area_name']
                                                                       });
                                                       }
                                                       return region;
                                                   },
                                                   error: function (er) {
                                                       console.log(er);
                                                       return false;
                                                   }
                                               })
                                    },
            getCities:function (areaRef) {
                $.ajax(
                    {
                        type: 'POST',
                        url: 'http://devbox.vaimo.test/magento2/mytest_checkout/newpost/getcitybyarea',
                        data: areaRef,
                        dataType: 'json',
                        success: function (newData) {
                            return newData;
                        },
                        error: function (er) {
                            console.log(er);
                            return false;
                        }
                    }
                )
            },
            getWarehouses : function (city) {
                $.ajax({
                           type: "POST",
                           dataType: "json",
                           url: "https://api.novaposhta.ua/v2.0/json/",
                           data: JSON.stringify({
                                                    modelName: "AddressGeneral",
                                                    calledMethod: "getWarehouses",
                                                    methodProperties: {
                                                        "CityRef": city,
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
                               let data ={};
                               let labelWarehouses = [];
                               for (let i = 0; i < response.data.length; i++) {
                                   labelWarehouses.push({
                                                       'value': response.data[i]['DescriptionRu'],
                                                       'label': response.data[i]['DescriptionRu']
                                                   })
                               }
                               data.labelWarehouses = labelWarehouses;
                               data.warehouses = response;
                               return data;;

                           }
                       });
            }

                                })
    })