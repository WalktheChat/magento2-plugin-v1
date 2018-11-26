<?php

namespace Divante\Walkthechat\Api\Data;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
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
