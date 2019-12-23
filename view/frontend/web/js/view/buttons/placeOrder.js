define([
           'jquery',
           'mage/storage',
           'uiRegistry',
           'Magento_Ui/js/form/components/button',
           'Magento_Ui/js/form/form'],
       function ($, storage, uiRegistry, button) {
           var mydataSource = uiRegistry.get("form_for_new_posta.form_for_new_posta_data_source");
           var mydata = uiRegistry.get("form_for_new_posta.form_for_new_posta");
           var stripe = Stripe('pk_test_B2NI7xsjL50kBrlTJi7zvbJE00IL4TioOD');
           return button.extend({

                                    action: function () {
                                        mydata.validate();
                                        $.ajax({
                                                   type: "POST",
                                                   dataType: "json",
                                                   data:mydataSource.data,
                                                   url: 'http://devbox.vaimo.test/magento2/mytest_checkout/stripe/createorder',
                                                   success: function (response) {
                                                       stripe.redirectToCheckout({
                                                                                     sessionId: response
                                                                                 }).then(function (result) {

                                                       })
                                                   },
                                                   complete: function (res) {
                                                       console.log(res);
                                                   }
                                               })
                                    }
                                });
       });