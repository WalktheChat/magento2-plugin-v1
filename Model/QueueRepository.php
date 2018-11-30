<?php

namespace Divante\Walkthechat\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */
class QueueRepository implements \Divante\Walkthechat\Api\QueueRepositoryInterface
{
    /**
     * @var ResourceQueue
     */
    protected $resource;

    /**
     * @var ResourceModel\Queue\CollectionFactory
     */
    protected $queueCollectionFactory;

    /**
     * @var \Divante\Walkthechat\Api\Data\QueueSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * QueueRepository constructor.
     *
     * @param ResourceQueue                                                      $resource
     * @param ResourceModel\Queue\CollectionFactory                              $queueCollectionFactory
     * @param \Divante\Walkthechat\Api\Data\QueueSearchResultsInterfaceFactory   $searchResultsFactory
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceModel\Queue $resource,
        ResourceModel\Queue\CollectionFactory $queueCollectionFactory,
        \Divante\Walkthechat\Api\Data\QueueSearchResultsInterfaceFactory $searchResultsFactory,
        \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource               = $resource;
        $this->queueCollectionFactory = $queueCollectionFactory;
        $this->searchResultsFactory   = $searchResultsFactory;
        $this->collectionProcessor    = $collectionProcessor;
    }

    /**
     * @param int $id
     *
     * @return \Divante\Walkthechat\Api\Data\QueueInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id)
    {
        $queue = $this->resource->load($id);
        if (!$queue->getId()) {
            throw new NoSuchEntityException(__('Queue with id "%1" does not exist.', $id));
        }

        return $queue;
    }

    /**
     * @param \Divante\Walkthechat\Api\Data\QueueInterface $queue
     *
     * @return \Divante\Walkthechat\Api\Data\QueueInterface
     * @throws CouldNotSaveException
     */
    public function save(\Divante\Walkthechat\Api\Data\QueueInterface $queue)
    {
        try {
            $this->resource->save($queue);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the queue: %1', $exception->getMessage()),
                $exception
            );
        }

        return $queue;
    }

    /**
     * @param \Divante\Walkthechat\Api\Data\QueueInterface $queue
     *
     * @return bool|void
     * @throws CouldNotDeleteException
     */
    public function delete(\Divante\Walkthechat\Api\Data\QueueInterface $queue)
    {
        try {
            $this->resource->delete($queue);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__('Could not delete the queue: %1', $exception->getMessage()));
        }

        return true;
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     *
     * @return \Divante\Walkthechat\Api\Data\QueueSearchResultInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $collection = $this->queueCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
