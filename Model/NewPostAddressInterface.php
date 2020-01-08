<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2020-01-03
 * Time: 12:23
 */

namespace Mytest\Checkout\Model;

/**
 * Interface NewPostAddressInterface
 * @package Mytest\Checkout\Model
 */
interface NewPostAddressInterface
{
    const TABLE_NAME = 'new_post_address';
    const FIELD_ID = 'entity_id';
    const FIELD_ADDRESS_ID = 'quote_id';
    const CITY_REF = 'city_ref';
    const AREA_REF = 'area_ref';
    const WAREHOUSE_REF = 'warehouse_ref';

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return mixed
     */
    public function getQuoteId();

    /**
     * @return mixed
     */
    public function getCityRef();

    /**
     * @return mixed
     */
    public function getAreaRef();

    /**
     * @return mixed
     */
    public function getWarehouseRef();

    /**
     * @param $id
     *
     * @return mixed
     */
    public function setQuoteId($id);

    /**
     * @param $ref
     *
     * @return mixed
     */
    public function setCityRef($ref);

    /**
     * @param $ref
     *
     * @return mixed
     */
    public function setAreaRef($ref);

    /**
     * @param $ref
     *
     * @return mixed
     */
    public function setWarehouseRef($ref);
}
