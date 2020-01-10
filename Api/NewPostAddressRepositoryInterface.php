<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2020-01-06
 * Time: 15:54
 */

namespace Mytest\Checkout\Api;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\{CouldNotDeleteException, CouldNotSaveException};
use Mytest\Checkout\Model\NewPostAddressInterface;

/**
 * Interface NewPostAddressRepositoryInterface
 * @package Mytest\Checkout\Api
 */
interface NewPostAddressRepositoryInterface
{
    /**
     * @param $id
     *
     * @return NewPostAddressInterface
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
     * @param NewPostAddressInterface $model
     *
     * @return NewPostAddressInterface
     * @throws CouldNotSaveException
     */
    public function save(NewPostAddressInterface $model);

    /**
     * @param NewPostAddressInterface $model
     *
     * @return $this
     * @throws CouldNotDeleteException
     */
    public function delete(NewPostAddressInterface $model);

    /**
     * @param $id
     *
     * @return bool|\Magento\Framework\DataObject
     */
    public function getByQuoteId($id);
}
