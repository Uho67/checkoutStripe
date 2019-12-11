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
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\HTTP\Client\Curl;
use Mytest\Checkout\Model\CityInterface;
use Mytest\Checkout\Model\CityFactory;
use Mytest\Checkout\Model\CityRepository;

class UpgradeData implements UpgradeDataInterface
{
    const KEY_NEW_POST = 'f5b54b3f7ce5800ca0ffcd95a4dbed15';
    const URL_NEW_POST = 'https://api.novaposhta.ua/v2.0/json/Address/getCities';

    private $curl;
    private $json;
    private $modelFactory;
    private $repository;
    public function __construct(
        CityFactory $cityFactory,
        CityRepository $repository,
        Curl $curl,
        Json $json
    ) {
        $this->repository = $repository;
        $this->modelFactory = $cityFactory;
        $this->json = $json;
        $this->curl = $curl;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.6', '<')) {
            $this->curl->setHeaders([
                'Content-Type'  => 'application/json'
            ]);
            $param = $this->json->serialize([
                'modelName'=>'Address',
                'calledMethod'=>'getCities',
                'apiKey'=>self::KEY_NEW_POST
            ]);
            $this->curl->post(self::URL_NEW_POST,$param);
            $request = $this->json->unserialize($this->curl->getBody());
            foreach ($request['data'] as $item) {

                $model = $this->modelFactory->create()->setData([
                    CityInterface::CITY_NAME=> $item['DescriptionRu'],
                    CityInterface::CITY_ID =>$item['CityID'],
                    CityInterface::CITY_REF=>$item['Ref'],
                    CityInterface::PARENT_AREA=>$item['Area']
                ]);
                $this->repository->save($model);
            }
        }
    }
}