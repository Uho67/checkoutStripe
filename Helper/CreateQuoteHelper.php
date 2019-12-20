<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-20
 * Time: 18:30
 */

namespace Mytest\Checkout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\ConfigurableProduct\Model\Product\Type\ConfigurableFactory;
use Magento\Quote\Model\QuoteFactory;

class CreateQuoteHelper extends AbstractHelper
{
    private $storeManager;
    private $productRepository;
    private $configurableFactory;
    private $quoteFactory;
    public function __construct(
        QuoteFactory $quoteFactory,
        ConfigurableFactory $configurableFactory,
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storeManager,
        Context $context)
    {
        $this->quoteFactory = $quoteFactory;
        $this->configurableFactory = $configurableFactory;
        $this->productRepository = $productRepository;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }
    private function getProducts($params)
    {

    }
    public function getQuote($paramsProducts)
    {
        $params = $paramsProducts;
        $storeId = $this->storeManager->getStore()->getId();
        $product = $this->productRepository->getById($params['product'], false, $storeId);
        $simpleProduct = $this->configurableFactory->create()
            ->getProductByAttributes($params['super_attribute'], $product);
        $store = $this->storeManager->getStore();
        $quote = $this->quoteFactory->create(); //Create object of quote
        $quote->setStore($store);
        $quote->setCurrency();
        $quote->addProduct($simpleProduct, 1);
        return $quote;
    }
}