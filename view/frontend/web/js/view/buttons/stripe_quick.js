define([
           'jquery',
           'Magento_Catalog/js/product/view/product-ids-resolver',
           'Magento_Ui/js/modal/modal',
           'Magento_Catalog/js/catalog-add-to-cart'
       ],
       function ($, idsResolver, modal, add_to_cart) {
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
                       var form = $(this).closest('form');
                       add_to_cart(form);
                       form.submit();
                           $('#mytest_checkout_newposta_popup').modal('openModal');
                   };
               }
       })