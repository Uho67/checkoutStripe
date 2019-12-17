<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-14
 * Time: 15:51
 */

namespace Mytest\Checkout\Controller\Stripe;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Cart;
use Magento\Quote\Model\Quote;

class CreateOrder extends Action
{
    private $cart;
    private $quote;
    public function __construct(
        Quote $quote,
        Cart $cart,
        Context $context
    ) {
        $this->quote = $quote;
        $this->cart = $cart;
        parent::__construct($context);
    }

    public function execute()
    {
        $this->quote = $this->cart->getQuote();
        return 1;
    }
}