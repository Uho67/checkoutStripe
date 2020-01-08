<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2020-01-06
 * Time: 15:54
 */

namespace Mytest\Checkout\Api;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\{CouldNotDeleteException,CouldNotSaveException};
use Mytest\Checkout\Model\NewPostAddressInterface;

interface NewPostAddressRepositoryInterface
{
    public function getById($id);

    public function deleteById($id);

    public function save(NewPostAddressInterface $model);

    public function delete(NewPostAddressInterface $model);

    public function getByQuoteId($id);
}
