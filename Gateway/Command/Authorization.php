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

/**
 * Class Authorization
 * @package Mytest\Checkout\Gateway\Command
 */
class Authorization implements AuthorizationInterface
{
    /**
     * @var SessionFactory
     */
    private $checkoutSessionFactory;
    /**
     * @var AutorizationStripeHelper
     */
    private $autorizationStripeHelper;

    /**
     * Authorization constructor.
     *
     * @param AutorizationStripeHelper $autorizationStripeHelper
     * @param SessionFactory $checkoutSessionFactory
     */
    public function __construct(
        AutorizationStripeHelper $autorizationStripeHelper,
        SessionFactory $checkoutSessionFactory
    ) {
        $this->autorizationStripeHelper = $autorizationStripeHelper;
        $this->checkoutSessionFactory = $checkoutSessionFactory;
    }

    /**
     * @return mixed|string
     */
    public function execute()
    {
        $order = $this->checkoutSessionFactory->create()->getLastRealOrder();
        return $this->autorizationStripeHelper->getIdSession($order);
    }
}
