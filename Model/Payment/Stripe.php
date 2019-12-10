<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-06
 * Time: 10:43
 */

namespace Mytest\Checkout\Model\Payment;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Payment\Model\Method\AbstractMethod;


class Stripe extends AbstractMethod
{
    const PAYMENT_METHOD_STRIPE_CODE = 'mytest_stripe';

    protected $_code = self::PAYMENT_METHOD_STRIPE_CODE;
}