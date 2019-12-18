define([
           'jquery',
           'mage/storage',
           'uiRegistry',
           'Magento_Ui/js/form/components/button',
           'Magento_Ui/js/form/form'],
       function ($, storage, uiRegistry, button) {
           var myFormSource = uiRegistry.get("form_for_new_posta.form_for_new_postadata_source");
           var myForm = uiRegistry.get("form_for_new_posta_form.form_for_new_posta_form");
           return button.extend({

                                    action: function () {
                                        myForm.validate();
                                        $.ajax(
                                            {
                                                type: 'POST',
                                                url: 'http://devbox.vaimo.test/magento2/mytest_checkout/stripe/getcitybyarea',
                                                data: myFormSource.data,
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
                                });
       });