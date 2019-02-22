<?php
/**
 * @package   Divante\Walkthechat
 *            
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Model\ResourceModel;

/**
 * Class Queue
 *
 * @package Divante\Walkthechat\Model\ResourceModel
 */
class Queue extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Table name
     *
     * @var string
     */
    const TABLE_NAME = 'divante_walkthechat_queue';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, \Divante\Walkthechat\Api\Data\QueueInterface::ID);
    }
}
