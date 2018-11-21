<?php
namespace Divante\Walkthechat\Model;

use Divante\Walkthechat\Model\ResourceModel\Queue as ResourceQueue;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Walkthechat Queue Repository
 *
 * @package  Divante\Walkthechat
 * @author   Divante Tech Team <tech@divante.pl>
 */
class QueueRepository implements \Divante\Walkthechat\Api\QueueRepositoryInterface
{
    /**
     * @var ResourceQueue
     */
    private $resource;

    /**
     * QueueRepository constructor.
     * @param ResourceQueue $resource
     */
    public function __construct(ResourceQueue $resource)
    {
        $this->resource = $resource;
    }

    /**
     * @param int $id
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
     * @return \Divante\Walkthechat\Api\Data\QueueInterface
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
     * @return void
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
}