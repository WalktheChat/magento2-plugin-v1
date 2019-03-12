<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Alex Yeremenko <madonzy13@gmail.com>
 * @copyright 2019 WalktheChat
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Api;

/**
 * Interface QueueRepositoryInterface
 *
 * @package Divante\Walkthechat\Api
 */
interface QueueRepositoryInterface
{
    /**
     * Return entity by ID
     *
     * @param int $id
     *
     * @return \Divante\Walkthechat\Api\Data\QueueInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * Saves entity
     *
     * @param \Divante\Walkthechat\Api\Data\QueueInterface $queue
     *
     * @return \Divante\Walkthechat\Api\Data\QueueInterface
     */
    public function save(\Divante\Walkthechat\Api\Data\QueueInterface $queue);

    /**
     * Remove entity
     *
     * @param \Divante\Walkthechat\Api\Data\QueueInterface $queue
     *
     * @return void
     */
    public function delete(\Divante\Walkthechat\Api\Data\QueueInterface $queue);

    /**
     * Return list of entities
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \Divante\Walkthechat\Api\Data\QueueSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Bulk save many entities
     *
     * @param array $data
     *
     * @return \Divante\Walkthechat\Api\Data\QueueSearchResultsInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function bulkSave(array $data);
}
