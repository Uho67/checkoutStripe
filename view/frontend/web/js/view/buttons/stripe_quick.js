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
       })