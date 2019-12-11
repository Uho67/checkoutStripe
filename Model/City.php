<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-11
 * Time: 10:59
 */

namespace Mytest\Checkout\Model;

use Magento\Framework\Model\AbstractModel;
use Mytest\Checkout\Model\ResourceModel\City as ResourceModel;

class City extends AbstractModel implements CityInterface
{
    public function _construct()
    {
        $this->_init(ResourceModel::class);
    }
    public function getId()
    {
        return $this->getData(CityInterface::FIELD_ID);
    }
    public function getName()
    {
        return $this->getData(CityInterface::CITY_NAME);
    }
    public function getRef()
    {
        return $this->getData(CityInterface::CITY_REF);
    }
    public function getCityId()
    {
        return $this->getData(CityInterface::CITY_ID);
    }
    public function getArea()
    {
        return $this->getData(CityInterface::PARENT_AREA);
    }

    public function setId($id)
    {
        $this->setData(CityInterface::FIELD_ID,$id);
    }
    public function setName($name)
    {
        $this->setData(CityInterface::CITY_NAME,$name);
    }
    public function setRef($ref)
    {
        $this->setData(CityInterface::CITY_REF,$ref);
    }
    public function setCityId($id)
    {
         $this->setData(CityInterface::CITY_ID,$id);
    }
    public function setArea($area)
    {
        $this->setData(CityInterface::PARENT_AREA,$area);
    }
}