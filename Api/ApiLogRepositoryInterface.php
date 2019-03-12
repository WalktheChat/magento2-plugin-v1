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
 * Interface ApiLogRepositoryInterface
 *
 * @package Divante\Walkthechat\Api
 */
interface ApiLogRepositoryInterface
{
    /**
     * Save ApiLog entity
     *
     * @param \Divante\Walkthechat\Api\Data\ApiLogInterface $log
     *
     * @return \Divante\Walkthechat\Api\Data\ApiLogInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException(
     */
    public function save(\Divante\Walkthechat\Api\Data\ApiLogInterface $log);

    /**
     * Return entity instance by ID
     *
     * @param int $id
     *
     * @return \Divante\Walkthechat\Api\Data\ApiLogInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * Return last item by for queue item id
     *
     * @param int $id
     *
     * @return \Divante\Walkthechat\Api\Data\ApiLogInterface
     */
    public function getLastByQuoteItemId($id);
}
