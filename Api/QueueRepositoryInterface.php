<?php
namespace Divante\Walkthechat\Api;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
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

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Divante\Walkthechat\Api\Data\QueueSearchResultInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
