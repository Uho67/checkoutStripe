<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-18
 * Time: 15:34
 */

namespace Mytest\Checkout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\HTTP\Client\CurlFactory;
use Magento\Framework\Serialize\Serializer\JsonFactory;

/**
 * Class AutorizationStripeHelper
 * @package Mytest\Checkout\Helper
 */
class AutorizationStripeHelper extends AbstractHelper
{
    /**
     * @var CurlFactory
     */
    private $curlFactory;
    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * AutorizationStripeHelper constructor.
     *
     * @param CurlFactory $curlFactory
     * @param JsonFactory $jsonFactory
     * @param Context $context
     */
    public function __construct(
        CurlFactory $curlFactory,
        JsonFactory $jsonFactory,
        Context $context
    ) {
        $this->curlFactory = $curlFactory;
        $this->jsonFactory = $jsonFactory;
        parent::__construct($context);
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     *
     * @return mixed
     */
    public function getIdSession($order)
    {
        $url = "https://api.stripe.com/v1/checkout/sessions";
        $params = [
            "line_items" =>
                [
                    [
                        'name' => 'My first purchase magento',
                        'description' => 'order № ' . $order->getRealOrderId(),
                        'amount' => (int)$order->getGrandTotal() * 100,
                        'currency' => $order->getOrderCurrency()->getData('currency_code'),
                        'quantity' => 1
                    ]
                ],
            "success_url" => "http://devbox.vaimo.test/newmagento",
            "cancel_url" => "http://devbox.vaimo.test/newmagento",
            "payment_method_types" => ['card']
        ];
        $curl = $this->curlFactory->create();
        $json = $this->jsonFactory->create();
        $curl->setHeaders([
            "Authorization" => "Bearer sk_test_HiVxybRBtnHAnvn0LFli0chJ00aixm12K2",
            "Content-Type" => "application/x-www-form-urlencoded",
        ]);
        $curl->post($url, $params);
        $response = $json->unserialize($curl->getBody());

        return $response['id'];
    }
}
