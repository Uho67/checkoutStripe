<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-06
 * Time: 15:40
 */

namespace Mytest\Checkout\Gateway\Command;

use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Checkout\Model\Session;

class Authorization implements AuthorizationInterface
{
    private $json;
    private $curl;
    private $checkoutSession;
    public function __construct(
        Json $json,
        Curl $curl,
        Session $checkoutSession
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->json = $json;
        $this->curl = $curl;
    }


    /**
     * @return string
     */
    public function execute()
    {

        $order = $this->checkoutSession->getLastRealOrder();
        $url = "https://api.stripe.com/v1/checkout/sessions";
        $params = [
            "line_items" =>
        [
            [
                'name' => 'My first purchase magento',
                'description' => 'order № '.$order->getRealOrderId(),
                'amount'  => (int)$order->getGrandTotal()*100,
                'currency' => $order->getOrderCurrency()->getData('currency_code'),
                'quantity' => 1

            ]
        ],
            "success_url"   => "http://devbox.vaimo.test/newmagento/mytest_checkout/stripe/afterstripe?XDEBUG_SESSION_START=netbeans-xdebug",
            "cancel_url"    => "http://devbox.vaimo.test/newmagento/mytest_checkout/stripe/afterstripe?XDEBUG_SESSION_START=netbeans-xdebug",
            "payment_method_types" => ['card']
        ];
        $this->curl->setHeaders([
            "Authorization" => "Bearer sk_test_HiVxybRBtnHAnvn0LFli0chJ00aixm12K2",
            "Content-Type"  => "application/x-www-form-urlencoded",
        ]);
        $this->curl->post($url,$params);
        $response = $this->json->unserialize($this->curl->getBody());
        return $response['id'];
    }
}