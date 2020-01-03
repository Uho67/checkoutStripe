define(
    [
        'ko',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/resource-url-manager',
        'mage/storage',
        'Magento_Checkout/js/model/payment-service',
        'Magento_Checkout/js/model/payment/method-converter',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Checkout/js/action/select-billing-address'
    ],
    function (
        ko,
        quote,
        resourceUrlManager,
        storage,
        paymentService,
        methodConverter,
        errorProcessor,
        fullScreenLoader,
        selectBillingAddressAction
    ) {
        'use strict';

        return {
            saveShippingInformation: function () {
                var payload,
                    shippingMethod = quote.shippingMethod().method_code + '_' + quote.shippingMethod().carrier_code,
                    newPostCity = registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.new_post_city'),
                    warehouse = registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.new_post_warehouse'),
                    regionId = registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.region_id');

                // if (shippingMethod == "vaimo_stripe_newpost_vaimo_stripe_newpost") {
                //     new_post_city = jQuery('[name="new_post_city"]').val();
                //     new_post_warehouse = jQuery('[name="new_post_warehouse"]').val();
                // }

                if (!quote.billingAddress()) {
                    selectBillingAddressAction(quote.shippingAddress());
                }
                payload = {
                    addressInformation: {
                        shipping_address: quote.shippingAddress(),
                        billing_address: quote.billingAddress(),
                        shipping_method_code: quote.shippingMethod().method_code,
                        shipping_carrier_code: quote.shippingMethod().carrier_code,
                        extension_attributes: {
                            new_post_address: {
                                'city_ref': newPostCity.value._latestValue,
                                'warehouse_ref': warehouse.value._latestValue,
                                'area_ref': regionId.value._latestValue
                            }
                        }
                    }
                };
                fullScreenLoader.startLoader();

                return storage.post(
                    resourceUrlManager.getUrlForSetShippingInformation(quote),
                    JSON.stringify(payload)
                ).done(
                    function (response) {
                        quote.setTotals(response.totals);
                        paymentService.setPaymentMethods(methodConverter(response.payment_methods));
                        fullScreenLoader.stopLoader();
                    }
                ).fail(
                    function (response) {
                        errorProcessor.process(response);
                        fullScreenLoader.stopLoader();
                    }
                );
            }
        };
    }
);