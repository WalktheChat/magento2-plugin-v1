<?php
/**
 * @package   Divante\Walkthechat
 *            
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Model\ResourceModel\ImageSync;

/**
 * Class Collection
 *
 * @package Divante\Walkthechat\Model\ResourceModel\ImageSync
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(
            \Divante\Walkthechat\Model\ImageSync::class,
            \Divante\Walkthechat\Model\ResourceModel\ImageSync::class
        );
    }
}
