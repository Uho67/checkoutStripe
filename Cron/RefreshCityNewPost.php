<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-12
 * Time: 14:51
 */

namespace Mytest\Checkout\Cron;

use Magento\Framework\HTTP\Client\CurlFactory;
use Magento\Framework\Serialize\Serializer\JsonFactory;
use Mytest\Checkout\Model\CityInterface;
use Mytest\Checkout\Model\CityFactory;
use Mytest\Checkout\Api\CityRepositoryInterface;
use Magento\Framework\App\ResourceConnectionFactory;

/**
 * Class RefreshCityNewPost
 * @package Mytest\Checkout\Cron
 */
class RefreshCityNewPost extends AbstractRefreshDataNewPost
{
    /**
     * api url for newPost
     */
    const URL_NEW_POST = 'https://api.novaposhta.ua/v2.0/json/Address/getCities';
    /**
     * @var CityFactory
     */
    private $modelFactory;
    /**
     * @var CityRepositoryInterface
     */
    private $repository;

    /**
     * RefreshCityNewPost constructor.
     *
     * @param CityFactory $modelFactory
     * @param CityRepositoryInterface $cityRepository
     * @param ResourceConnectionFactory $resourceConnectionFactory
     * @param CurlFactory $curlFactory
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        CityFactory $modelFactory,
        CityRepositoryInterface $cityRepository,
        ResourceConnectionFactory $resourceConnectionFactory,
        CurlFactory $curlFactory,
        JsonFactory $jsonFactory
    ) {
        $this->modelFactory = $modelFactory;
        $this->repository = $cityRepository;
        parent::__construct($resourceConnectionFactory, $curlFactory, $jsonFactory);
    }

    /**
     * refresh bd
     */
    public function execute()
    {
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
        $resourceConnection = $this->resourceConnectionFactory->create();
        $tableName = $resourceConnection->getTableName(CityInterface::TABLE_NAME);
        $connection = $resourceConnection->getConnection();
        $connection->truncateTable($tableName);
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
}
