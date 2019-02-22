<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Api\Data;

/**
 * Interface ImageSyncSearchResultsInterface
 *
 * @package Divante\Walkthechat\Api\Data
 */
interface ImageSyncSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return \Divante\Walkthechat\Api\Data\ImageSyncInterface[]
     */
    public function getItems();

    /**
     * @param \Divante\Walkthechat\Api\Data\ImageSyncInterface[] $items
     *
     * @return void
     */
    public function setItems(array $items);
}
