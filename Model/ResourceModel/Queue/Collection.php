<?php
namespace Divante\Walkthechat\Model\ResourceModel\Queue;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
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

    /**
     * Add filter by only ready for sync
     *
     * @return $this
     */
    public function addOnlyForSyncFilter()
    {
        $this->getSelect()->where(
            'main_table.processed_at IS NULL'
        );

        return $this;
    }
}