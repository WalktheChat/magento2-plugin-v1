<?php
/**
 * @package   Divante\Walkthechat
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
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
}
