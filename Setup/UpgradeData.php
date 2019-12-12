<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-11
 * Time: 13:04
 */

namespace Mytest\Checkout\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Serialize\Serializer\JsonFactory;
use Magento\Framework\HTTP\Client\CurlFactory;
use Mytest\Checkout\Model\CityInterface;
use Mytest\Checkout\Model\CityFactory;
use Mytest\Checkout\Api\CityRepositoryInterface;
use Mytest\Checkout\Model\AreaInterface;
use Magento\Framework\App\ResourceConnectionFactory;

class UpgradeData implements UpgradeDataInterface
{
    const KEY_NEW_POST = 'f5b54b3f7ce5800ca0ffcd95a4dbed15';
    const URL_NEW_POST = 'https://api.novaposhta.ua/v2.0/json/Address/getCities';
    private $curlFactory;
    private $jsonFactory;
    private $modelFactory;
    private $repository;
    private $resourceConnectionFactory;

    public function __construct(
        ResourceConnectionFactory $resourceConnectionFactory,
        CityFactory $cityFactory,
        CityRepositoryInterface $repository,
        CurlFactory $curlFactory,
        JsonFactory $jsonFactory
    ) {
        $this->resourceConnectionFactory = $resourceConnectionFactory;
        $this->repository = $repository;
        $this->modelFactory = $cityFactory;
        $this->jsonFactory = $jsonFactory;
        $this->curlFactory = $curlFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.9', '<')) {
            $curl = $this->curlFactory->create();
            $json = $this->jsonFactory->create();
            $curl->setHeaders([
                'Content-Type' => 'application/json'
            ]);
            $param = $json->serialize([
                'modelName' => 'Address',
                'calledMethod' => 'getCities',
                'apiKey' => self::KEY_NEW_POST
            ]);
            $curl->post(self::URL_NEW_POST, $param);
            $request = $json->unserialize($curl->getBody());
            foreach ($request['data'] as $item) {

                $model = $this->modelFactory->create()->setData([
                    CityInterface::CITY_NAME => $item['DescriptionRu'],
                    CityInterface::CITY_ID => $item['CityID'],
                    CityInterface::CITY_REF => $item['Ref'],
                    CityInterface::PARENT_AREA => $item['Area']
                ]);
                $this->repository->save($model);
            }
        }
        if (version_compare($context->getVersion(), '1.0.9', '<')) {
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
            $connection->insertMultiple($tableName, $data);
        }
    }
}
