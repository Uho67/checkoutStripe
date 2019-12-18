<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-06
 * Time: 15:40
 */

namespace Mytest\Checkout\Gateway\Command;

use Magento\Checkout\Model\SessionFactory;
use Mytest\Checkout\Helper\AutorizationStripeHelper;

class Authorization implements AuthorizationInterface
{
    private $checkoutSessionFactory;
    private $autorizationStripeHelper;

    public function __construct(
        AutorizationStripeHelper $autorizationStripeHelper,
        SessionFactory $checkoutSessionFactory
    ) {
        $this->autorizationStripeHelper = $autorizationStripeHelper;
        $this->checkoutSessionFactory = $checkoutSessionFactory;
    }

    /**
     * @return string
     */
    public function execute()
    {
        $order = $this->checkoutSessionFactory->create()->getLastRealOrder();
        return $this->autorizationStripeHelper->getIdSession($order);
    }
}
