<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-11
 * Time: 10:43
 */

namespace Mytest\Checkout\Model;

interface CityInterface
{
    const FIELD_ID = 'entity_id';
    const CITY_ID = 'city_id';
    const CITY_NAME = 'city_name';
    const CITY_REF  = 'city_ref';
    const PARENT_AREA = 'area';
    const TABLE_NAME = 'new_post_city';

    public function getId();
    public function getCityId();
    public function getName();
    public function getRef();
    public function getArea();


    public function setId($id);
    public function setCityId($id);
    public function setName($name);
    public function setRef($ref);
    public function setArea($area);
}