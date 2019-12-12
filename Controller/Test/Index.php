<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-11
 * Time: 09:22
 */

namespace Mytest\Checkout\Controller\Test;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\HTTP\Client\Curl;
use Mytest\Checkout\Model\AreaInterface;

class Index extends Action
{
    const KEY_NEW_POST = 'f5b54b3f7ce5800ca0ffcd95a4dbed15';
    const URL_NEW_POST = 'http://testapi.novaposhta.ua/v2.0/json/Address/getAreas';
    private $resourceConnection;
    private $curl;
    private $json;

    public function __construct(
        Context $context,
        Curl $curl,
        Json $json,
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->json = $json;
        $this->curl = $curl;
        parent::__construct($context);
    }

    public function execute()
    {
        $this->curl->setHeaders([
            'Content-Type'  => 'application/json'
        ]);
        $param = $this->json->serialize([
            'modelName'=>'Address',
            'calledMethod'=>'getAreas',
            'apiKey'=>self::KEY_NEW_POST
            ]);
        $this->curl->post(self::URL_NEW_POST,$param);
        $request = $this->json->unserialize($this->curl->getBody());
        $data = array();
        foreach ($request['data'] as $item) {

            $data[] = [
                AreaInterface::AREA_NAME => $item['Description'],
                AreaInterface::AREA_REF => $item['Ref']
            ];
        }
        $tableName = $this->resourceConnection->getTableName(AreaInterface::TABLE_NAME);
        $connection = $this->resourceConnection->getConnection();
        $connection->insertMultiple($tableName, $data);
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;

    }
}