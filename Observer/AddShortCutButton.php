<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-10
 * Time: 08:33
 */

namespace Mytest\Checkout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Mytest\Checkout\Block\Buttons\QuickStripe as Button;

class AddShortCutButton implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        $shortcutButtons = $observer->getEvent()->getContainer();
        $shortcut = $shortcutButtons->getLayout()->createBlock(
            Button::class,
            '',
            []
        );
        $shortcutButtons->addShortcut($shortcut);
    }
}