<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2020-01-06
 * Time: 16:04
 */

namespace Mytest\Checkout\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Mytest\Checkout\Model\NewPostAddressInterface;

/**
 * Class NewPostAddress
 * @package Mytest\Checkout\Model\ResourceModel
 */
class NewPostAddress extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(NewPostAddressInterface::TABLE_NAME, NewPostAddressInterface::FIELD_ID);
    }
}
