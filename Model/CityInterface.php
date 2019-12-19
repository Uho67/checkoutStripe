<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-11
 * Time: 10:43
 */

namespace Mytest\Checkout\Model;

/**
 * Interface CityInterface
 * @package Mytest\Checkout\Model
 * model for action with new post city
 */
interface CityInterface
{
    const FIELD_ID = 'entity_id';
    const CITY_ID = 'city_id';
    const CITY_NAME = 'city_name';
    const CITY_REF  = 'city_ref';
    const PARENT_AREA = 'area';
    const TABLE_NAME = 'new_post_city';

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return mixed
     */
    public function getCityId();

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @return mixed
     */
    public function getRef();

    /**
     * @return mixed
     */
    public function getArea();

    /**
     * @param $id
     *
     * @return mixed
     */
    public function setId($id);

    /**
     * @param $id
     *
     * @return mixed
     */
    public function setCityId($id);

    /**
     * @param $name
     *
     * @return mixed
     */
    public function setName($name);

    /**
     * @param $ref
     *
     * @return mixed
     */
    public function setRef($ref);

    /**
     * @param $area
     *
     * @return mixed
     */
    public function setArea($area);
}
