define([
           'uiRegistry',
           'jquery',
           'Magento_Ui/js/modal/modal',
           'jquery/ui',
           'mage/mage'
       ], function (registry, $, modal) {
    'use strict';

    $.widget('mage.vaimoStripeNewPost', {
        options: {
            originalForm:
                'form:not(#product_addtocart_form_from_popup):has(input[name="product"][value=%1])',
            productId: 'input[type="hidden"][name="product"]'
        },
        _create: function () {
            this.element.on('click', '[data-action="checkout-form-submit"]', $.proxy(function (e) {
                var $target = $(e.target),
                    productId = $target.closest('form').find(this.options.productId).val(),
                    originalForm = this.options.originalForm.replace('%1', productId);

                e.preventDefault();

                this._redirect(originalForm);
            }, this));
        },

        _redirect: function (originalForm) {
            var form = originalForm ? $(originalForm) : $($(this).closest('form'));
            form.mage('validation', {});
            this.submitForm(form)

        },
        submitForm: function (form) {
            this.ajaxSubmit(form);
        },

        /**
         * @param {jQuery} form
         */
        ajaxSubmit: function (form) {
            var sendUrl;
            var formData;
            if (form[0]) {
                sendUrl = form[0].action.replace('checkout/cart/add', 'mytest_checkout/stripe/InformationAboutOrder');
                formData = new FormData(form[0]);
            } else {
                sendUrl = 'http://devbox.vaimo.test/magento2/mytest_checkout/stripe/InformationAboutOrder'
                formData = null;
            }
            $.ajax({
                       url: sendUrl,
                       data: formData,
                       type: 'post',
                       dataType: 'json',
                       cache: false,
                       contentType: false,
                       processData: false,

                       /** @inheritdoc */
                       success: function (res) {
                           if (res) {
                               var formParams = registry.get('form_for_new_posta.form_for_new_posta.general.productsParams');
                               if (res.products) {
                                   var myParams = JSON.stringify(res.products);
                                   formParams.setOptions([{'value': myParams, 'label': '1'}]);
                               }
                               if (res.customer) {
                                   var email = registry.get('form_for_new_posta.form_for_new_posta.general.email'),
                                       firstname = registry.get('form_for_new_posta.form_for_new_posta.general.firstname'),
                                       lastname = registry.get('form_for_new_posta.form_for_new_posta.general.lastname');
                                   email.value(res.customer.email);
                                   lastname.value(res.customer.lastname);
                                   firstname.value(res.customer.firstname);
                                   if (res.customer.phone) {
                                       var telephone = registry.get('form_for_new_posta.form_for_new_posta.general.telephone');
                                       telephone.value(res.customer.phone);
                                   }

                               }
                               var options = {
                                   type: 'popup',
                                   responsive: true,
                                   innerScroll: true,
                                   title: 'Event order',
                                   buttons: []
                               };
                               modal(options, $('#mytest_checkout_newposta_popup'));
                               $('#mytest_checkout_newposta_popup').modal('openModal')
                           }
                       },

                       /** @inheritdoc */
                       complete: function (res) {
                           console.log(res);
                       }
                   });
        }
    });

    return $.mage.vaimoStripeNewPost;
});
