/* @api */
define([
           'uiComponent',
           'Magento_Checkout/js/model/payment/renderer-list'
       ], function (Component, rendererList) {
    'use strict';

    rendererList.push(
        {
            type: 'mytest_stripe',
            component: 'Mytest_Checkout/js/view/payment/renderer/mytest_stripe_renderer'
        }
    );

    /** Add view logic here if needed */
    return Component.extend({});
});