<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2020-01-06
 * Time: 16:05
 */

namespace Mytest\Checkout\Model\ResourceModel\NewPostAddress;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Mytest\Checkout\Model\ResourceModel\NewPostAddress
 */
class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(\Mytest\Checkout\Model\NewPostAddress::class, \Mytest\Checkout\Model\ResourceModel\NewPostAddress::class);
    }

}