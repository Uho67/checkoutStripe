<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2020-01-02
 * Time: 22:15
 */

namespace Mytest\Checkout\Plugin\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessor;

/**
 * Class LayoutProcessorPlugin
 * @package Mytest\Checkout\Plugin\Checkout
 */
class LayoutProcessorPlugin
{
    /**
     * @param LayoutProcessor $subject
     * @param array $jsLayout
     *
     * @return array
     */
    public function afterProcess(
        LayoutProcessor $subject,
        array $jsLayout
    ) {
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['new_post_city'] = [
            'component' => "Magento_Ui/js/form/element/select",
            'config' => [
                'customScope' => 'customShippingMethodFields',
                'template' => 'ui/form/field',
                'elementTmpl' => "ui/form/element/select",
                'id' => "new_post_city"
            ],
            'dataScope' => 'new_post_city',
            'label' => "City",
            'provider' => 'checkoutProvider',
            'visible' => false,
            'sortOrder' => 81,
            'id' => 'new_post_city'
        ];
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['new_post_warehouse'] = [
            'component' => "Magento_Ui/js/form/element/select",
            'config' => [
                'customScope' => 'customShippingMethodFields',
                'template' => 'ui/form/field',
                'elementTmpl' => "ui/form/element/select",
                'id' => "new_post_warehouse"
            ],
            'dataScope' => 'new_post_warehouse',
            'label' => "Warehouse",
            'provider' => 'checkoutProvider',
            'visible' => false,
            'sortOrder' => 82,
            'id' => 'new_post_warehouse'
        ];

        return $jsLayout;
    }
}
