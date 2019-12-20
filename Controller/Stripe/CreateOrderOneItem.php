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
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Quote\Model\QuoteFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Backend\Model\SessionFactory;

class CreateOrderOneItem extends Action
{
    private $productRepository;
    private $configurable;
    private $storeManager;
    private $quoteFactory;
    private $sessionFactory;
    private $jsonFactory;

    public function __construct(
        JsonFactory $jsonFactory,
        SessionFactory $sessionFactory,
        QuoteFactory $quoteFactory,
        StoreManagerInterface $storeManager,
        Configurable $configurable,
        ProductRepositoryInterface $productRepository,
        Context $context
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->sessionFactory = $sessionFactory;
        $this->quoteFactory = $quoteFactory;
        $this->storeManager = $storeManager;
        $this->configurable = $configurable;
        $this->productRepository = $productRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $json = $this->jsonFactory->create();
        $params = $this->getRequest()->getParams();
        $productId = (int)$this->getRequest()->getParam('product');
        if ($productId) {
            $session = $this->sessionFactory->create();
            $session->setVaimoMytestParamsForQuote($params);

            return $json->setData(['yes' => true]);
        } else {
            return $json->setData(['yes' => false]);
        }
    }
}