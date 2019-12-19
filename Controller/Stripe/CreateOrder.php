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
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Controller\Result\JsonFactory as ResultJsonFactory;
use Magento\Checkout\Model\CartFactory;
use Mytest\Checkout\Helper\CreateOrderHelper;
use Mytest\Checkout\Helper\AutorizationStripeHelper;
use Mytest\Checkout\Model\ResourceModel\City\CollectionFactory;
use Mytest\Checkout\Model\AreaInterface;
use Mytest\Checkout\Model\CityInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class CreateOrder
 * @package Mytest\Checkout\Controller\Stripe
 */
class CreateOrder extends Action
{
    /**
     * @var ResourceConnection
     */
    private $resource;
    /**
     * @var CreateOrderHelper
     */
    private $createOrderHelper;
    /**
     * @var ResultJsonFactory
     */
    private $resultJsonFactory;
    /**
     * @var CartFactory
     */
    private $cartFactory;
    /**
     * @var AutorizationStripeHelper
     */
    private $autorizationStripeHelper;
    /**
     * @var CollectionFactory
     */
    private $cityCollectionFactory;

    /**
     * CreateOrder constructor.
     *
     * @param ResourceConnection $resourceConnection
     * @param CollectionFactory $collectionFactory
     * @param AutorizationStripeHelper $autorizationStripeHelper
     * @param CartFactory $cartFactory
     * @param ResultJsonFactory $resultJsonFactory
     * @param CreateOrderHelper $createOrderHelper
     * @param Context $context
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        CollectionFactory $collectionFactory,
        AutorizationStripeHelper $autorizationStripeHelper,
        CartFactory $cartFactory,
        ResultJsonFactory $resultJsonFactory,
        CreateOrderHelper $createOrderHelper,
        Context $context
    ) {
        $this->resource = $resourceConnection;
        $this->cityCollectionFactory = $collectionFactory;
        $this->autorizationStripeHelper = $autorizationStripeHelper;
        $this->cartFactory = $cartFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->createOrderHelper = $createOrderHelper;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Json|ResultInterface
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        $tempOrder = $this->getOrderData($params);
        $order = $this->createOrderHelper->createMageOrder($tempOrder, $this->cartFactory->create()->getQuote());
        $resultJson = $this->resultJsonFactory->create();
        if ($order->getRealOrderId()) {
            $id = $this->autorizationStripeHelper->getIdSession($order);
            return $resultJson->setData($id);
        } else {
            return $resultJson->setData($order);
        }
    }

    /**
     * @param $params
     *
     * @return array
     *
     * get Data for shipping
     */
    private function getOrderData($params)
    {
        $cityRef = $params['city'];
        $city = $this->cityCollectionFactory->create()
            ->addFieldToFilter(CityInterface::CITY_REF,['eq'=>$cityRef])
            ->toOptionArray();
        /**
         * get area || region
         */
        $connection = $this->resource->getConnection();
        $tableName = $connection->getTableName(AreaInterface::TABLE_NAME);
        $areaData = $connection->fetchRow($connection->select()
            ->from($tableName)
            ->where(AreaInterface::AREA_REF.'=?',$params['area']));

        $tempOrder = [
            'email' => $params['email'],
            //buyer email id
            'shipping_address' => [
                'firstname' => $params['firstname'],
                //address Details
                'lastname' => $params['lastname'],
                'street' => $params['street'],
                'city' => $city[0]['label'],
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
