<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-16
 * Time: 17:30
 */

namespace Mytest\Checkout\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Quote\Model\QuoteManagement;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Framework\App\Helper\AbstractHelper;

class CreateOrderHelper extends AbstractHelper
{
    private $quoteManagement;
    private $customerFactory;
    private $customerRepository;
    private $_storeManager;
    private $quoteRepository;

    public function __construct(
        CartRepositoryInterface $cartRepository,
        QuoteManagement $quoteManagement,
        CustomerFactory $customerFactory,
        CustomerRepositoryInterface $customerRepository,
        StoreManagerInterface $storeManager,
        Context $context
    ) {
        $this->quoteRepository = $cartRepository;
        $this->quoteManagement = $quoteManagement;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    public function createMageOrder($orderData, $quote)
    {
        $store = $this->_storeManager->getStore();
        $websiteId = $this->_storeManager->getStore()->getWebsiteId();
        $customer = $this->customerFactory->create();
        $customer->setWebsiteId($websiteId);
        $customer->loadByEmail($orderData['email']);
        if (!$customer->getEntityId()) {
            //If not avilable then create this customer
            $customer->setWebsiteId($websiteId)
                ->setStore($store)
                ->setFirstname($orderData['shipping_address']['firstname'])
                ->setLastname($orderData['shipping_address']['lastname'])
                ->setEmail($orderData['email'])
                ->setPassword($orderData['email']);
            $customer->save();
        }
        $customer = $this->customerRepository->getById($customer->getEntityId());
        $quote->assignCustomer($customer); //Assign quote to customer
        //Set Address to quote
        $quote->getBillingAddress()->addData($orderData['shipping_address']);
        $quote->getShippingAddress()->addData($orderData['shipping_address']);
        // Collect Rates and Set Shipping & Payment Method
        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod('flatrate_flatrate'); //shipping method
        $quote->setPaymentMethod('mytest_stripe'); //payment method
        $quote->setInventoryProcessed(false); //not effetc inventory
        // Set Sales Order Payment
        $quote->getPayment()->importData(['method' => 'mytest_stripe']);
        // Collect Totals & Save Quote
        $quote->collectTotals();
        $this->quoteRepository->save($quote);
        // Create Order From Quote
        $order = $this->quoteManagement->submit($quote);
        $order->setEmailSent(0);
        if ($order->getEntityId()) {
            $result = $order;
        } else {
            $result = [
                'error' => 1,
                'msg' => 'Your custom message'
            ];
        }

        return $result;
    }
}