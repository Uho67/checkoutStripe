define([
           'jquery',
           'mage/storage',
           'uiRegistry',
           'Magento_Ui/js/form/components/button',
           'Magento_Ui/js/form/form'],
       function ($, storage, uiRegistry, button) {
           var mydataSource = uiRegistry.get("funnyorderfront_form.funnyorderfront_form_data_source");
           var mydata = uiRegistry.get("funnyorderfront_form.funnyorderfront_form");
           return button.extend({

                                    action: function () {
                                        $.ajax({
                                                   type: "POST",
                                                   dataType: "json",
                                                   url: 'http://devbox.vaimo.test/newmagento/mytest_checkout/stripe/createorder',
                                                   success:function (response) {
                                                       console.log(response)
                                                   }
                                               })
                                    }
                                });
       });