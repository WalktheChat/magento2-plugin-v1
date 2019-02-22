<?php
/**
 * @package   Divante\Walkthechat
 *            
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Model\ResourceModel\Queue;

/**
 * Class Collection
 *
 * @package Divante\Walkthechat\Model\ResourceModel\Queue
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Divante\Walkthechat\Model\Queue::class, \Divante\Walkthechat\Model\ResourceModel\Queue::class);
    }
}
