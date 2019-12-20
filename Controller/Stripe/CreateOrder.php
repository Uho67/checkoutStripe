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
use Magento\Framework\Controller\Result\JsonFactory as ResultJsonFactory;
use Magento\Checkout\Model\CartFactory;
use Mytest\Checkout\Helper\CreateOrderHelper;
use Mytest\Checkout\Helper\AutorizationStripeHelper;
use Magento\Backend\Model\SessionFactory;
use Mytest\Checkout\Helper\CreateQuoteHelper;

class CreateOrder extends Action
{
    private $createOrderHelper;
    private $resultJsonFactory;
    private $cartFactory;
    private $autorizationStripeHelper;
    private $sessionFactory;
    private $createQuoteHelper;

    public function __construct(
        CreateQuoteHelper $createQuoteHelper,
        SessionFactory $sessionFactory,
        AutorizationStripeHelper $autorizationStripeHelper,
        CartFactory $cartFactory,
        ResultJsonFactory $resultJsonFactory,
        CreateOrderHelper $createOrderHelper,
        Context $context
    ) {
        $this->createQuoteHelper = $createQuoteHelper;
        $this->sessionFactory = $sessionFactory;
        $this->autorizationStripeHelper = $autorizationStripeHelper;
        $this->cartFactory = $cartFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->createOrderHelper = $createOrderHelper;
        parent::__construct($context);
    }

    public function execute()
    {
        $orderParams = $this->getRequest()->getParams();
        $session = $this->sessionFactory->create();
        if ($session->getVaimoMytestParamsForQuote()) {
            $productParams = $session->getVaimoMytestParamsForQuote();
            $quote = $this->createQuoteHelper->getQuote($productParams);
            $session->setVaimoMytestParamsForQuote(null);
        } else {
            $quote = $this->cartFactory->create()->getQuote();
        }
        $order = $this->createOrderHelper->createMageOrder($orderParams, $quote);
        $resultJson = $this->resultJsonFactory->create();
        if ($order->getRealOrderId()) {
            $id = $this->autorizationStripeHelper->getIdSession($order);

            return $resultJson->setData($id);
        } else {
            return $resultJson->setData($order);
        }
    }
}
