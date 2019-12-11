define([
           'jquery',
           'Magento_Catalog/js/product/view/product-ids-resolver',
           'Magento_Ui/js/modal/modal'
       ],
       function ($, idsResolver, modal) {
               return function (config, node) {
                   var options = {
                       type: 'popup',
                       responsive: true,
                       innerScroll: true,
                       title: 'Event order',
                       buttons: []
                   };
                   modal(options, $('#mytest_checkout_newposta_popup'));
                   node.onclick = function () {
                       $('#mytest_checkout_newposta_popup').modal('openModal');
                   };
               }

               // node.onclick = function() {
               //     let form = $(node).closest('form');
               //     let formData = new FormData(form[0]);
               //     console.log(formData);
               //
               //     $.ajax({
               //                url: "http://devbox.vaimo.test/newmagento/mytest_checkout/stripe/oneproductcreateorder",
               //                data: formData,
               //                type: 'post',
               //                dataType: 'json',
               //                cache: false,
               //                contentType: false,
               //                processData: false,
               //                /** @inheritdoc */
               //                success: function (res) {
               //                    console.log(res);
               //                }
               //
               //            })
               // }

       })