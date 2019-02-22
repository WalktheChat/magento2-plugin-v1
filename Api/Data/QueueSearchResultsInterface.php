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
 * Interface QueueSearchResultsInterface
 *
 * @package Divante\Walkthechat\Api\Data
 */
interface QueueSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return \Divante\Walkthechat\Api\Data\QueueInterface[]
     */
    public function getItems();

    /**
     * @param \Divante\Walkthechat\Api\Data\QueueInterface[] $items
     *
     * @return void
     */
    public function setItems(array $items);
}
