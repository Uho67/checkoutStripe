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

/**
 * Class City
 * @package Mytest\Checkout\Model
 */
class City extends AbstractModel implements CityInterface
{
    public function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->getData(CityInterface::FIELD_ID);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->getData(CityInterface::CITY_NAME);
    }

    /**
     * @return mixed
     */
    public function getRef()
    {
        return $this->getData(CityInterface::CITY_REF);
    }

    /**
     * @return mixed
     */
    public function getCityId()
    {
        return $this->getData(CityInterface::CITY_ID);
    }

    /**
     * @return mixed
     */
    public function getArea()
    {
        return $this->getData(CityInterface::PARENT_AREA);
    }

    /**
     * @param mixed $id
     *
     * @return AbstractModel|void
     */
    public function setId($id)
    {
        $this->setData(CityInterface::FIELD_ID,$id);
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->setData(CityInterface::CITY_NAME,$name);
    }

    /**
     * @param $ref
     */
    public function setRef($ref)
    {
        $this->setData(CityInterface::CITY_REF,$ref);
    }

    /**
     * @param $id
     */
    public function setCityId($id)
    {
         $this->setData(CityInterface::CITY_ID,$id);
    }

    /**
     * @param $area
     */
    public function setArea($area)
    {
        $this->setData(CityInterface::PARENT_AREA,$area);
    }
}
