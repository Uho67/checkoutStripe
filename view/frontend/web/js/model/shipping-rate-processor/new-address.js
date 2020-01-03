define([
           'uiRegistry',
           'jquery',
           'Magento_Checkout/js/model/resource-url-manager',
           'Magento_Checkout/js/model/quote',
           'mage/storage',
           'Magento_Checkout/js/model/shipping-service',
           'Magento_Checkout/js/model/shipping-rate-registry',
           'Magento_Checkout/js/model/error-processor'
       ], function (registry, $, resourceUrlManager, quote, storage, shippingService, rateRegistry, errorProcessor) {
    'use strict';

    return {
        /**
         * Get shipping rates for specified address.
         * @param {Object} address
         */
        getRates: function (address) {
            var cache, serviceUrl, payload,
                newPostCity = registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.new_post_city'),
                warehouse = registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.new_post_warehouse'),
                regionId = registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.region_id');

            shippingService.isLoading(true);
            cache = rateRegistry.get(address.getCacheKey());
            serviceUrl = resourceUrlManager.getUrlForEstimationShippingMethodsForNewAddress(quote);
            payload = JSON.stringify({
                                         address: {
                                             'street': address.street,
                                             'city': address.city,
                                             'region_id': address.regionId,
                                             'region': address.region,
                                             'country_id': address.countryId,
                                             'postcode': address.postcode,
                                             'email': address.email,
                                             'customer_id': address.customerId,
                                             'firstname': address.firstname,
                                             'lastname': address.lastname,
                                             'middlename': address.middlename,
                                             'prefix': address.prefix,
                                             'suffix': address.suffix,
                                             'vat_id': address.vatId,
                                             'company': address.company,
                                             'telephone': address.telephone,
                                             'fax': address.fax,
                                             'custom_attributes': address.customAttributes,
                                             'save_in_address_book': address.saveInAddressBook,
                                             extension_attributes: {
                                                 new_post_address: {
                                                     'city_ref': newPostCity.value._latestValue,
                                                     'warehouse_ref': warehouse.value._latestValue,
                                                     'area_ref': regionId.value._latestValue
                                                 }
                                             }
                                         }
                                     }
            );

            if (cache) {
                shippingService.setShippingRates(cache);
                shippingService.isLoading(false);
            } else {
                storage.post(
                    serviceUrl, payload, false
                ).done(function (result) {
                    rateRegistry.set(address.getCacheKey(), result);
                    shippingService.setShippingRates(result);
                }).fail(function (response) {
                    shippingService.setShippingRates([]);
                    errorProcessor.process(response);
                }).always(function () {
                    shippingService.isLoading(false);
                });
            }
        }
    };
});