<?php
/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
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
}
