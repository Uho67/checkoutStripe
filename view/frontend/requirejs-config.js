var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/select-shipping-method': {
                'Mytest_Checkout/js/mixin/checkout/select-shipping-method-mixin': true
            }
        }
    },
    "map": {
        "*":
            {
                "Magento_Checkout/js/model/shipping-save-processor/default": "Mytest_Checkout/js/mixin/checkout/shipping-save-processor",
                'Magento_Checkout/js/model/shipping-rate-processor/new-address': 'Mytest_Checkout/js/model/shipping-rate-processor/new-address'
            }
    }

}

