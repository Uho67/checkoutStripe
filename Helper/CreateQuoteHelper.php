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
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class CreateQuoteHelper
 * @package Mytest\Checkout\Helper
 */
class CreateQuoteHelper extends AbstractHelper
{
    const TYPE_OF_PRODUCT_CONFIGURABLE = 'configurable';
    const TYPE_OF_PRODUCT_BUNDLE = 'bundle';
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var ConfigurableFactory
     */
    private $configurableFactory;
    /**
     * @var QuoteFactory
     */
    private $quoteFactory;

    /**
     * CreateQuoteHelper constructor.
     *
     * @param QuoteFactory $quoteFactory
     * @param ConfigurableFactory $configurableFactory
     * @param ProductRepositoryInterface $productRepository
     * @param StoreManagerInterface $storeManager
     * @param Context $context
     */
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

    /**
     * @param $paramsProducts
     *
     * @return array
     * @throws NoSuchEntityException
     */
    private function getProducts($paramsProducts)
    {
        $storeId = $this->storeManager->getStore()->getId();
        $product = $this->productRepository->getById($paramsProducts['product'], false, $storeId);
        switch ($product->getTypeId()) {
            case self::TYPE_OF_PRODUCT_CONFIGURABLE :
                $product = $this->configurableFactory->create()
                    ->getProductByAttributes($paramsProducts['super_attribute'], $product);

                return [
                    [
                        'product' => $product,
                        'qty' => intval($paramsProducts['qty'])
                    ]
                ];
            case self::TYPE_OF_PRODUCT_BUNDLE:
                $allProducts = [];
                $bundleCollection = $product->getTypeInstance(true)
                    ->getSelectionsCollection(
                        $product->getTypeInstance(true)->getOptionsIds($product),
                        $product
                    );
                for ($i = 1; $i <= count($paramsProducts['bundle_option']); $i++) {
                    $bundleProduct = $bundleCollection->getItemById($paramsProducts['bundle_option'][$i]);
                    $allProducts[] = [
                        'product' => $this->productRepository->getById($bundleProduct->getId()),
                        'qty' => intval($paramsProducts['bundle_option_qty'][$i] * intval($paramsProducts['qty']))
                    ];
                }

                return $allProducts;
            default:
                return [
                    [
                        'product' => $product,
                        'qty' => intval($paramsProducts['qty'])
                    ]
                ];
        }
    }

    /**
     * @param $paramsProducts
     *
     * @return Quote
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getQuote($paramsProducts)
    {
        $store = $this->storeManager->getStore();
        $quote = $this->quoteFactory->create();
        $quote->setStore($store);
        $quote->setCurrency();
        foreach ($this->getProducts($paramsProducts) as $productInfo) {
            $quote->addProduct($productInfo['product'], $productInfo['qty']);
        }

        return $quote;
    }
}
