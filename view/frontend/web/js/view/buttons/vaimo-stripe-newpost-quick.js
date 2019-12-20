define([
           'jquery',
           'Magento_Ui/js/modal/modal',
           'jquery/ui',
           'mage/mage'
       ], function ($, modal) {
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
            this.submitForm(form)

        },
        submitForm: function (form) {
            this.ajaxSubmit(form);
        },

        /**
         * @param {jQuery} form
         */
        ajaxSubmit: function (form) {
            var oldUrl = form[0].action;
            form[0].action = oldUrl.replace('checkout/cart/add', 'mytest_checkout/stripe/createorderoneitem');
            var formData = new FormData(form[0]);

            $.ajax({
                       url: form.attr('action'),
                       data: formData,
                       type: 'post',
                       dataType: 'json',
                       cache: false,
                       contentType: false,
                       processData: false,

                       /** @inheritdoc */
                       success: function (res) {
                           console.log(res)
                       },

                       /** @inheritdoc */
                       complete: function (res) {
                           form[0].action = oldUrl;
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
                   });
        }
    });

    return $.mage.vaimoStripeNewPost;
});
