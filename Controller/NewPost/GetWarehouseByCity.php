<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-16
 * Time: 15:08
 */

namespace Mytest\Checkout\Controller\NewPost;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Serialize\Serializer\JsonFactory;
use Magento\Framework\HTTP\Client\CurlFactory;
use Mytest\Checkout\Cron\AbstractRefreshDataNewPost;


class GetWarehouseByCity extends Action
{
    const URL_NEW_POST = 'http://testapi.novaposhta.ua/v2.0/xml/AddressGeneral/getWarehouses';
    private $jsonFactory;
    private $curlFactory;
    public function __construct(
        JsonFactory $jsonFactory,
        CurlFactory $curlFactory,
        Context $context
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->curlFactory = $curlFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $param = $this->getRequest()->getParams()['cityRef'];
        $curl = $this->curlFactory->create();
        $json = $this->jsonFactory->create();
        $curl->setHeaders([
            'Content-Type' => 'application/json'
        ]);
        $param = $json->serialize([
            'modelName' => 'Address',
            'calledMethod' => 'getWarehouses',
            'apiKey' => AbstractRefreshDataNewPost::KEY_NEW_POST,
            "CityRef"=> $param
        ]);
        $curl->post(self::URL_NEW_POST, $param);

        $request = $curl->getBody();
        return 'ljkdfjkdd';
    }
}