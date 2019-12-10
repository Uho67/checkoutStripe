/* @api */
define([
           'Magento_Checkout/js/view/payment/default',
           'mage/storage',
           'jquery'
       ], function (Component, storage, $) {
    'use strict';
    var stripe = Stripe('pk_test_B2NI7xsjL50kBrlTJi7zvbJE00IL4TioOD');
    return Component.extend({
                                defaults: {
                                    template: 'Mytest_Checkout/payment/mytest_checkout'
                                },
                                redirectAfterPlaceOrder: false,
                                /**
                                 * After place order callback
                                 */
                                afterPlaceOrder: function () {
                                    var serviceUrl = 'http://devbox.vaimo.test/newmagento/rest/all/V1/authorization';
                                    $.ajax({
                                               url: serviceUrl,
                                               success: function (response) {
                                                   stripe.redirectToCheckout({
                                                                                 sessionId: response
                                                                             }).then(function (result) {

                                                   })
                                               }
                                           })
                                }
                            });
});