// var config = {
//    config: {
//        mixins: {
//            'Magento_Checkout/js/view/shipping': {
//                'Dckap_CustomFields/js/view/shipping': true
//            }
//        }
//    },
//    "map": {
//        "*": {
//            "Magento_Checkout/js/model/shipping-save-processor/default" : "Dckap_CustomFields/js/shipping-save-processor"
//        }
//    }
// };
var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/select-shipping-method': {
                'Mytest_Checkout/js/mixin/checkout/select-shipping-method-mixin': true
            }
        }
    }
};
