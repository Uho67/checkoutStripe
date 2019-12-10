<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-10
 * Time: 09:03
 */

namespace Mytest\Checkout\Block\Buttons;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Block\ShortcutInterface;

class QuickStripe extends Template implements ShortcutInterface
{
        protected $_template = 'Mytest_Checkout::buttons/quick_stripe.phtml';
        const ALIAS_ELEMENT_INDEX = 'mytest.checkout.quick.stripe';

        public function getAlias()
        {
            return self::ALIAS_ELEMENT_INDEX;
        }
}