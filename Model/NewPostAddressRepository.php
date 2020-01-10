<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2020-01-06
 * Time: 15:58
 */

namespace Mytest\Checkout\Model;

use Mytest\Checkout\Api\NewPostAddressRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Mytest\Checkout\Model\ResourceModel\NewPostAddress\CollectionFactory;
use Mytest\Checkout\Model\ResourceModel\NewPostAddress as ResourceModel;
use Mytest\Checkout\Model\NewPostAddressFactory;
use Magento\Framework\Exception\{CouldNotDeleteException,CouldNotSaveException};

/**
 * Class NewPostAddressRepository
 * @package Mytest\Checkout\Model
 */
class NewPostAddressRepository implements NewPostAddressRepositoryInterface
{
    private $resourceModel;
    private $modelFactory;
    private $collectionFactory;

    /**
     * NewPostAddressRepository constructor.
     *
     * @param ResourceModel $resourceModel
     * @param NewPostAddressFactory $newPostAddressFactory
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        ResourceModel $resourceModel,
        NewPostAddressFactory $newPostAddressFactory,
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->resourceModel = $resourceModel;
        $this->modelFactory = $newPostAddressFactory;
    }

    /**
     * @param $id
     *
     * @return NewPostAddressInterface
     * @throws NoSuchEntityException
     */
    public function getById($id)
    {
        $model = $this->modelFactory->create();
        $this->resourceModel->load($model, $id);
        if (!$model->getId()) {
            throw new NoSuchEntityException(__('Model with id "%1" does not exist.', $id));
        } else {
            return $model;
        }
    }

    /**
     * @param $id
     *
     * @throws CouldNotDeleteException
     */
    public function deleteById($id)
    {
        try {
            $this->delete($this->getById($id));
        } catch (NoSuchEntityException $e) {
        }
    }

    /**
     * @param NewPostAddressInterface $model
     *
     * @return $this
     * @throws CouldNotDeleteException
     */
    public function delete(NewPostAddressInterface $model)
    {
        try {
            $this->resourceModel->delete($model);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return $this;
    }

    /**
     * @param NewPostAddressInterface $model
     *
     * @return NewPostAddressInterface
     * @throws CouldNotSaveException
     */
    public function save(NewPostAddressInterface $model)
    {
        try {
            $oldModel = $this->getByQuoteId($model->getQuoteId());
            if($oldModel){
                $model->setId($oldModel->getId());
            }
            $this->resourceModel->save($model);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $model;
    }

    /**
     * @param $id
     *
     * @return bool|\Magento\Framework\DataObject
     */
    public function getByQuoteId($id)
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter(NewPostAddressInterface::FIELD_ADDRESS_ID, ['eq'=>$id]);
        if($collection->getItems()) {
            foreach ($collection->getItems() as $item) {
                return $item;
            }
        }
        return false;
    }
}
