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
use Mytest\Checkout\Block\Buttons\QuickStripe;
use Magento\Payment\Gateway\ConfigInterface;
use Magento\Catalog\Block\ShortcutButtons;

/**
 * Class AddShortCutButton
 * @package Mytest\Checkout\Observer
 */
class AddShortCutButton implements ObserverInterface
{
    const PATH_PATTERN_SHIPPING = 'carriers/vaimo_stripe_newpost/%s';
    const PATH_PATTERN_PAYMENT = 'payment/mytest_stripe/%s';
    /**
     * @var ConfigInterface
     */
    private $config;
    /**
     * @var ShortcutButtons
     */
    private $shortcutButtons;

    /**
     * AddShortCutButton constructor.
     *
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param Observer $observer
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(Observer $observer)
    {
        if (!$this->config->getStripeValue('active') || !$this->config->getNewPostValue('active')) {
            return;
        }
        $this->shortcutButtons = $observer->getEvent()->getContainer();
        $shortcut = $this->shortcutButtons->getLayout()->createBlock(
            QuickStripe::class,
            '',
            []
        );
        $this->shortcutButtons->addShortcut($shortcut);
    }
}
