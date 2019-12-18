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
use Magento\Quote\Model\Quote;
use Mytest\Checkout\Helper\CreateOrderHelper;
use Magento\Framework\Controller\Result\JsonFactory as ResultJsonFactory;
use Magento\Checkout\Model\CartFactory;
use Mytest\Checkout\Helper\AutorizationStripeHelper;

class CreateOrder extends Action
{
    private $quote;
    private $createOrderHelper;
    private $resultJsonFactory;
    private $cartFactory;
    private $autorizationStripeHelper;

    public function __construct(
        AutorizationStripeHelper $autorizationStripeHelper,
        CartFactory $cartFactory,
        ResultJsonFactory $resultJsonFactory,
        CreateOrderHelper $createOrderHelper,
        Quote $quote,
        Context $context
    ) {
        $this->autorizationStripeHelper = $autorizationStripeHelper;
        $this->cartFactory = $cartFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->createOrderHelper = $createOrderHelper;
        $this->quote = $quote;
        parent::__construct($context);
    }

    public function execute()
    {
        $tempOrder = [
            'email' => 'oworld@mageplaza.com',
            //buyer email id
            'shipping_address' => [
                'firstname' => ')))))))))))',
                //address Details
                'lastname' => '((((((((((((',
                'street' => '123 Demo',
                'city' => 'Mageplaza',
                'country_id' => 'UA',
                'region_id' => '3',
                'region' => 'zzz',
                'postcode' => '10019',
                'telephone' => '0123456789',
                'fax' => '',
                'save_in_address_book' => 1
            ]
        ];
        $params = $this->getRequest()->getParams();
        $order = $this->createOrderHelper->createMageOrder($tempOrder, $this->cartFactory->create()->getQuote());
        $resultJson = $this->resultJsonFactory->create();
        if ($order->getRealOrderId()) {
            $id = $this->autorizationStripeHelper->getIdSession($order);
            return $resultJson->setData($id);
        } else {
            return $resultJson->setData($order);
        }
    }
}