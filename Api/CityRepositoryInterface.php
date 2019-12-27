<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-11
 * Time: 12:41
 */

namespace Mytest\Checkout\Api;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\{CouldNotDeleteException,CouldNotSaveException};
use Mytest\Checkout\Model\CityInterface;

/**
 * Interface CityRepositoryInterface
 * @package Mytest\Checkout\Api
 */
interface CityRepositoryInterface
{
    /**
     * @param $id
     *
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param $id
     *
     * @throws CouldNotDeleteException
     */
    public function deleteById($id);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param CityInterface $model
     *
     * @return CityInterface
     * @throws CouldNotSaveException
     */
    public function save(CityInterface $model);

    /**
     * @param CityInterface $model
     *
     * @return $this
     * @throws CouldNotDeleteException
     */
    public function delete(CityInterface $model);
}
