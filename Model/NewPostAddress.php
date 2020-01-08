<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2020-01-03
 * Time: 12:28
 */

namespace Mytest\Checkout\Model;

use Magento\Framework\Model\AbstractModel;
use Mytest\Checkout\Model\ResourceModel\NewPostAddress as ResourceModel;

/**
 * Class NewPostAddress
 * @package Mytest\Checkout\Model
 */
class NewPostAddress extends AbstractModel implements NewPostAddressInterface
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
        return $this->getData(NewPostAddressInterface::FIELD_ID);
    }

    /**
     * @return mixed
     */
    public function getQuoteId()
    {
        return $this->getData(NewPostAddressInterface::FIELD_ADDRESS_ID);
    }

    /**
     * @return mixed
     */
    public function getCityRef()
    {
        return $this->getData(NewPostAddressInterface::CITY_REF);
    }

    /**
     * @return mixed
     */
    public function getAreaRef()
    {
        return $this->getData(NewPostAddressInterface::AREA_REF);
    }

    /**
     * @return mixed
     */
    public function getWarehouseRef()
    {
        return $this->getData(NewPostAddressInterface::WAREHOUSE_REF);
    }

    /**
     * @param $id
     *
     * @return mixed|void
     */
    public function setQuoteId($id)
    {
        $this->setData(NewPostAddressInterface::FIELD_ADDRESS_ID, $id);
    }

    /**
     * @param $ref
     *
     * @return mixed|void
     */
    public function setAreaRef($ref)
    {
        $this->setData(NewPostAddressInterface::AREA_REF, $ref);
    }

    /**
     * @param $ref
     *
     * @return mixed|void
     */
    public function setCityRef($ref)
    {
        $this->setData(NewPostAddressInterface::CITY_REF, $ref);
    }

    /**
     * @param $ref
     *
     * @return mixed|void
     */
    public function setWarehouseRef($ref)
    {
        $this->setData(NewPostAddressInterface::WAREHOUSE_REF, $ref);
    }
}
