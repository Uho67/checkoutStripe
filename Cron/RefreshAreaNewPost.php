<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-12
 * Time: 14:40
 */

namespace Mytest\Checkout\Cron;

use Mytest\Checkout\Model\AreaInterface;

class RefreshAreaNewPost extends AbstractRefreshDataNewPost
{
    const URL_NEW_POST = 'https://api.novaposhta.ua/v2.0/json/Address/getCities';

    public function execute()
    {
        $curl = $this->curlFactory->create();
        $json = $this->jsonFactory->create();
        $curl->setHeaders([
            'Content-Type' => 'application/json'
        ]);
        $param = $json->serialize([
            'modelName' => 'Address',
            'calledMethod' => 'getAreas',
            'apiKey' => self::KEY_NEW_POST
        ]);
        $curl->post(self::URL_NEW_POST, $param);
        $request = $json->unserialize($curl->getBody());
        $data = array();
        foreach ($request['data'] as $item) {

            $data[] = [
                AreaInterface::AREA_NAME => $item['Description'],
                AreaInterface::AREA_REF => $item['Ref']
            ];
        }
        $resourceConnection = $this->resourceConnectionFactory->create();
        $tableName = $resourceConnection->getTableName(AreaInterface::TABLE_NAME);
        $connection = $resourceConnection->getConnection();
        $connection->truncateTable($tableName);
        $connection->insertMultiple($tableName, $data);
    }
}