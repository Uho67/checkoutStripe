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
use Magento\Framework\Controller\Result\JsonFactory as ResultJsonFactory;
use Magento\Checkout\Model\CartFactory;
use Mytest\Checkout\Helper\CreateOrderHelperFactory;
use Mytest\Checkout\Helper\AutorizationStripeHelperFactory;
use Mytest\Checkout\Helper\CreateQuoteHelperFactory;
use Magento\Framework\Webapi\Rest\Request\Deserializer\Json as Deserializer;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Webapi\Exception;

/**
 * Class CreateOrder
 * @package Mytest\Checkout\Controller\Stripe
 */
class CreateOrder extends Action
{
    /**
     * @var CreateOrderHelperFactory
     */
    private $createOrderHelperFactory;
    /**
     * @var ResultJsonFactory
     */
    private $resultJsonFactory;
    /**
     * @var CartFactory
     */
    private $cartFactory;
    /**
     * @var AutorizationStripeHelperFactory
     */
    private $autorizationStripeHelperFactory;
    /**
     * @var CreateQuoteHelperFactory
     */
    private $createQuoteHelperFactory;
    /**
     * @var Deserializer
     */
    private $deserializer;

    /**
     * CreateOrder constructor.
     *
     * @param Deserializer $deserializer
     * @param CreateQuoteHelperFactory $createQuoteHelperFactory
     * @param AutorizationStripeHelperFactory $autorizationStripeHelperFactory
     * @param CartFactory $cartFactory
     * @param ResultJsonFactory $resultJsonFactory
     * @param CreateOrderHelperFactory $createOrderHelperFactory
     * @param Context $context
     */
    public function __construct(
        Deserializer $deserializer,
        CreateQuoteHelperFactory $createQuoteHelperFactory,
        AutorizationStripeHelperFactory $autorizationStripeHelperFactory,
        CartFactory $cartFactory,
        ResultJsonFactory $resultJsonFactory,
        CreateOrderHelperFactory $createOrderHelperFactory,
        Context $context
    ) {
        $this->deserializer = $deserializer;
        $this->createQuoteHelperFactory = $createQuoteHelperFactory;
        $this->autorizationStripeHelperFactory = $autorizationStripeHelperFactory;
        $this->cartFactory = $cartFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->createOrderHelperFactory = $createOrderHelperFactory;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Json|ResultInterface
     * @throws LocalizedException
     * @throws NoSuchEntityException
     * @throws Exception
     */
    public function execute()
    {
        $orderParams = $this->getRequest()->getParams();
        if (!empty($orderParams['productsParams'])) {
            $productParams = $this->deserializer->deserialize($orderParams['productsParams']);
            $quote = $this->createQuoteHelperFactory->create()->getQuote($productParams);
        } else {
            $quote = $this->cartFactory->create()->getQuote();
        }
        $order = $this->createOrderHelperFactory->create()->createMageOrder($orderParams, $quote);
        $resultJson = $this->resultJsonFactory->create();
        if ($order->getRealOrderId()) {
            $id = $this->autorizationStripeHelperFactory->create()->getIdSession($order);

            return $resultJson->setData($id);
        } else {
            return $resultJson->setData($order);
        }
    }
}
