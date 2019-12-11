<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-10
 * Time: 12:26
 */

namespace Mytest\Checkout\Controller\Stripe;

use Magento\Framework\App\Action\Action;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Checkout\Model\CartFactory;

class OneProductCreateOrder extends Action
{
    private $cart;
    private $storeManager;
    private $productRepository;
    private $searchCriteriaBuilderFactory;
    public function __construct(
        CartFactory $cart,
        StoreManagerInterface $storeManager,
        Context $context,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
    ) {
        $this->cart = $cart;
        $this->storeManager = $storeManager;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->productRepository = $productRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        if (!$this->getRequest()->isPost()) {
            throw new \Magento\Framework\Exception\NotFoundException(__('Page not found.'));
        }

        $params = $this->getRequest()->getParams();

        try {
            if (isset($params['qty'])) {
                $filter = new \Zend_Filter_LocalizedToNormalized(
                    ['locale' => $this->_objectManager->get(
                        \Magento\Framework\Locale\ResolverInterface::class
                    )->getLocale()]
                );
                $params['qty'] = $filter->filter($params['qty']);
            }

            $product = $this->_initProduct();
            $price = $product->getPrice();
            $related = $this->getRequest()->getParam('related_product');
            $cart = $this->cart->create();
            $cart->addProduct($product, $params);

            /**
             * Check product availability
             */
            if (!$product) {
                throw new \Magento\Framework\Exception\NotFoundException(__('Product was not find'));
            }

            if (!empty($related)) {
                $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
                $productIds = explode(',', $related);
                $searchCriteria = $searchCriteriaBuilder->addFilter('entity_id', $productIds, 'in')->create();
                $products = $this->productRepository->getList($searchCriteria)->getItems();
            }


        } catch (\Exception $e) {
            $this->messageManager->addWarningMessage($e, __('We can\'t add this item to your shopping cart right now.'));
            return;
        }
    }

    protected function _initProduct()
    {
        $productId = (int)$this->getRequest()->getParam('product');
        if ($productId) {
            $storeId = $this->storeManager->getStore()->getId();
            try {
                return $this->productRepository->getById($productId, false, $storeId);
            } catch (NoSuchEntityException $e) {
                return false;
            }
        }
        return false;
    }
}