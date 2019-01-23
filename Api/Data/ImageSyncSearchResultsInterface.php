<?php
/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
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