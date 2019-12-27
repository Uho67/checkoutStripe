<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-19
 * Time: 14:35
 */

namespace Mytest\Checkout\Controller\Stripe;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Customer\Model\SessionFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class InformationAboutOrder
 * @package Mytest\Checkout\Controller\Stripe
 */
class InformationAboutOrder extends Action
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var JsonFactory
     */
    private $jsonFactory;
    /**
     * @var SessionFactory
     */
    private $sessionFactory;
    /**
     * @var Session
     */
    private $session;

    /**
     * CreateOrderOneItem constructor.
     *
     * @param SessionFactory $sessionFactory
     * @param JsonFactory $jsonFactory
     * @param StoreManagerInterface $storeManager
     * @param ProductRepositoryInterface $productRepository
     * @param Context $context
     */
    public function __construct(
        SessionFactory $sessionFactory,
        JsonFactory $jsonFactory,
        StoreManagerInterface $storeManager,
        ProductRepositoryInterface $productRepository,
        Context $context
    ) {
        $this->sessionFactory = $sessionFactory;
        $this->jsonFactory = $jsonFactory;
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
        parent::__construct($context);
    }

    /**
     * @return array|ResponseInterface|Json|ResultInterface
     * if order came from pdp we must give back information about product
     */
    public function execute()
    {
        $productParams = $this->getRequest()->getParams();
        if (!empty($productParams)) {
            if (intval($productParams['qty']) === 0) {
                $this->messageManager->addWarningMessage(__(" Qty can not be 0 "));

                return [];
            }
            if (isset($productParams['super_attribute'])) {
                foreach ($productParams['super_attribute'] as $atribute) {
                    if ($atribute == "") {
                        $this->messageManager->addWarningMessage(__("You should to choose atribute"));

                        return [];
                    }
                }
            }
        } else {
            $productParams = false;
        }
        $json = $this->jsonFactory->create();
        $customerData = $this->getCustomerData();

        return $json->setData([
            'products' => $productParams,
            'customer' => $customerData
        ]);
    }

    /**
     * @return \Magento\Customer\Model\Session
     */
    private function getSession()
    {
        if (null === $this->session) {
            $this->session = $this->sessionFactory->create();
        }

        return $this->session;
    }

    /**
     * @return array|bool
     * if customer is autozationed
     */
    private function getCustomerData()
    {
        $session = $this->getSession();
        $customerData = [];
        if ($session->getCustomerId()) {
            $customer = $session->getCustomerData();
            $customerData['firstname'] = $customer->getFirstname();
            $customerData['lastname'] = $customer->getLastname();
            $customerData['email'] = $customer->getEmail();
            if ($customer->getAddresses()[0]->getTelephone()) {
                $customerData['phone'] = $customer->getAddresses()[0]->getTelephone();
            } else {
                $customerData['phone'] = false;
            }

            return $customerData;
        }

        return false;
    }
}
