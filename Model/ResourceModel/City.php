<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-11
 * Time: 11:05
 */

namespace Mytest\Checkout\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Mytest\Checkout\Model\CityInterface;

class City extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(CityInterface::TABLE_NAME, CityInterface::FIELD_ID);
    }
}