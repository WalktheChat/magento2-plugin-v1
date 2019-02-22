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
 * Class ApiLog
 *
 * @package Divante\Walkthechat\Model\ResourceModel
 */
class ApiLog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Logs table
     *
     * @var string
     */
    const TABLE_NAME = 'divante_walkthechat_api_log';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, \Divante\Walkthechat\Api\Data\ApiLogInterface::ENTITY_ID_FIELD);
    }
}
