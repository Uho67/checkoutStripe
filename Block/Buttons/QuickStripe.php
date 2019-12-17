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

/**
 * Class QuickStripe
 * @package Mytest\Checkout\Block\Buttons
 * block for quick order NewPost + Stripe
 */
class QuickStripe extends Template implements ShortcutInterface
{
    /**
     * @var string
     */
    protected $_template = 'Mytest_Checkout::buttons/quick_stripe.phtml';
    /**
     * alias which is used by shorcutContainer
     */
    const ALIAS_ELEMENT_INDEX = 'mytest.checkout.quick.stripe';

    /**
     * @return string
     */
    public function getAlias()
    {
        return self::ALIAS_ELEMENT_INDEX;
    }
}
