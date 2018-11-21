<?php
namespace Divante\Walkthechat\Model\ResourceModel\Queue;

/**
 * Walkthechat Queue Resource Collection
 *
 * @package  Divante\Walkthechat
 * @author   Divante Tech Team <tech@divante.pl>
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
        $this->_init('Divante\Walkthechat\Model\Queue', 'Divante\Walkthechat\Model\ResourceModel\Queue');
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