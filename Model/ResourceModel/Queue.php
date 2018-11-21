<?php
namespace Divante\Walkthechat\Model\ResourceModel;

/**
 * Walkthechat Queue Resource Model
 *
 * @package  Divante\Walkthechat
 * @author   Divante Tech Team <tech@divante.pl>
 */
class Queue extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('walkthechat_queue', 'entity_id');
    }
}