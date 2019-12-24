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

class CreateOrderOneItem extends Action
{
    private $productRepository;
    private $storeManager;
    private $jsonFactory;
    private $sessionFactory;
    private $session;

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

    private function getSession()
    {
        if (null === $this->session) {
            $this->session = $this->sessionFactory->create();
        }

        return $this->session;
    }

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