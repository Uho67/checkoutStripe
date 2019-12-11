<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-11
 * Time: 12:41
 */

namespace Mytest\Checkout\Api;

interface CityRepositoryInterface
{
    public function getById($id);


    public function deleteById($id);


    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);


    public function save(\Mytest\Checkout\Model\CityInterface $model);


    public function delete(\Mytest\Checkout\Model\CityInterface $model);
}