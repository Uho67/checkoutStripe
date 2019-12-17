<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-11
 * Time: 12:45
 */

namespace Mytest\Checkout\Model;

use Mytest\Checkout\Api\CityRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Mytest\Checkout\Model\ResourceModel\City\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\Search\SearchResultInterfaceFactory;
use Mytest\Checkout\Model\ResourceModel\City as ResourceModel;
use Mytest\Checkout\Model\CityFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\{CouldNotDeleteException,CouldNotSaveException};

/**
 * Class CityRepository
 * @package Mytest\Checkout\Model
 */
class CityRepository implements CityRepositoryInterface
{
    /**
     * @var \Mytest\Checkout\Model\CityFactory
     */
    private $cityFactory;
    /**
     * @var ResourceModel
     */
    private $resourceModel;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;
    /**
     * @var SearchResultInterfaceFactory
     */
    private $searchResultFactory;

    /**
     * CityRepository constructor.
     *
     * @param ResourceModel $resourceModel
     * @param \Mytest\Checkout\Model\CityFactory $cityFactory
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchResultInterfaceFactory $searchResultFactory
     */
    public function __construct(
        ResourceModel $resourceModel,
        CityFactory $cityFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultInterfaceFactory $searchResultFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultFactory = $searchResultFactory;
        $this->resourceModel = $resourceModel;
        $this->cityFactory = $cityFactory;
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getById($id)
    {
        $funnyOrder = $this->cityFactory->create();
        $this->resourceModel->load($funnyOrder, $id);
        if (!$funnyOrder->getId()) {
            throw new NoSuchEntityException(__('Order with id "%1" does not exist.', $id));
        } else {
            return $funnyOrder;
        }
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
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
     * @param CityInterface $model
     *
     * @return $this
     * @throws CouldNotDeleteException
     */
    public function delete(CityInterface $model)
    {
        try {
            $this->resourceModel->delete($model);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return $this;
    }

    /**
     * @param CityInterface $model
     *
     * @return CityInterface
     * @throws CouldNotSaveException
     */
    public function save(CityInterface $model)
    {
        try {
            $this->resourceModel->save($model);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $model;
    }
}
