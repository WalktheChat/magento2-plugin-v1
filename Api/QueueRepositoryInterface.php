<?php
namespace Divante\Walkthechat\Api;

/**
 * Walkthechat Queue Repository Interface
 *
 * @package  Divante\Walkthechat
 * @author   Divante Tech Team <tech@divante.pl>
 */
interface QueueRepositoryInterface
{
    /**
     * @param int $id
     * @return \Divante\Walkthechat\Api\Data\QueueInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param \Divante\Walkthechat\Api\Data\QueueInterface $queue
     * @return \Divante\Walkthechat\Api\Data\QueueInterface
     */
    public function save(\Divante\Walkthechat\Api\Data\QueueInterface $queue);

    /**
     * @param \Divante\Walkthechat\Api\Data\QueueInterface $queue
     * @return void
     */
    public function delete(\Divante\Walkthechat\Api\Data\QueueInterface $queue);
}
