<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-17
 * Time: 15:14
 */

namespace Mytest\Checkout\Controller\NewPost;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Mytest\Checkout\Model\ResourceModel\City\CollectionFactory;
use Mytest\Checkout\Model\CityInterface;

class GetCityByArea extends Action
{
    private $collectionFactory;
    private $jsonFactory;
    public function __construct(JsonFactory $jsonFactory, CollectionFactory $collectionFactory, Context $context)
    {
        $this->jsonFactory = $jsonFactory;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $areaRef = $this->getRequest()->getParam('areaRef');
        $option = $this->collectionFactory->create()
             ->addFieldToFilter(CityInterface::PARENT_AREA,['eq'=>$areaRef])
             ->toOptionArray();
        $json = $this->jsonFactory->create();
        return $json->setData($option);
    }
}