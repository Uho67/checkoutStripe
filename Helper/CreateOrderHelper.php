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
use Mytest\Checkout\Model\AreaInterface;
use Mytest\Checkout\Model\CityInterface;
use Mytest\Checkout\Model\ResourceModel\City\CollectionFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Quote\Model\Quote;
use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class CreateOrderHelper
 * @package Mytest\Checkout\Helper
 */
class CreateOrderHelper extends AbstractHelper
{
    private $quoteManagement;
    private $customerFactory;
    private $customerRepository;
    private $_storeManager;
    private $quoteRepository;
    private $cityCollectionFactory;
    private $resource;

    /**
     * CreateOrderHelper constructor.
     *
     * @param ResourceConnection $resourceConnection
     * @param CollectionFactory $collectionFactory
     * @param CartRepositoryInterface $cartRepository
     * @param QuoteManagement $quoteManagement
     * @param CustomerFactory $customerFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param StoreManagerInterface $storeManager
     * @param Context $context
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        CollectionFactory $collectionFactory,
        CartRepositoryInterface $cartRepository,
        QuoteManagement $quoteManagement,
        CustomerFactory $customerFactory,
        CustomerRepositoryInterface $customerRepository,
        StoreManagerInterface $storeManager,
        Context $context
    ) {
        $this->resource = $resourceConnection;
        $this->cityCollectionFactory = $collectionFactory;
        $this->quoteRepository = $cartRepository;
        $this->quoteManagement = $quoteManagement;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * @param $orderParams
     * @param Quote $quote
     *
     * @return array|AbstractExtensibleModel|OrderInterface|object|null
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function createMageOrder($orderParams, $quote)
    {
        $orderData = $this->getOrderData($orderParams);
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
            ->setShippingMethod('vaimo_stripe_newpost_vaimo_stripe_newpost'); //shipping method
        $quote->setPaymentMethod('mytest_stripe'); //payment method
        $quote->setInventoryProcessed(false); //not effetc inventory
        $quote->save();
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

    /**
     * @param $params
     *
     * @return array
     */
    private function getOrderData($params)
    {
        $cityRef = $params['city'];
        $city = $this->cityCollectionFactory->create()
            ->addFieldToFilter(CityInterface::CITY_REF, ['eq' => $cityRef])
            ->toOptionArray();
        /**
         * get area || region
         */
        $connection = $this->resource->getConnection();
        $tableName = $connection->getTableName(AreaInterface::TABLE_NAME);
        $areaData = $connection->fetchRow($connection->select()
            ->from($tableName)
            ->where(AreaInterface::AREA_REF . '=?', $params['area']));
        $tempOrder = [
            'email' => $params['email'],
            //buyer email id
            'shipping_address' => [
                'firstname' => $params['firstname'],
                //address Details
                'lastname' => $params['lastname'],
                'street' => $params['street'],
                'city' => $city[0]['label'],
                'region_code'=> $params['city'],
                'country_id' => 'UA',
                'region_id' => $areaData['entity_id'],
                'region' => $areaData['area_name'],
                'postcode' => '10019',
                'telephone' => $params['telephone'],
                'fax' => '',
                'save_in_address_book' => 1
            ]
        ];

        return $tempOrder;
    }
}
