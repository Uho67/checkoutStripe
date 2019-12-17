<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-11
 * Time: 12:36
 */

namespace Mytest\Checkout\Model\ResourceModel\City;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Mytest\Checkout\Model\CityInterface;

/**
 * Class Collection
 * @package Mytest\Checkout\Model\ResourceModel\City
 */
class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(\Mytest\Checkout\Model\City::class, \Mytest\Checkout\Model\ResourceModel\City::class);
    }

    public function toOptionArray()
    {
        return $this->_toOptionArray(CityInterface::CITY_REF, CityInterface::CITY_NAME, []);
    }
}
