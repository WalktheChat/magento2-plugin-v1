<?php
/**
 * @package   Divante\Walkthechat
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
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
    const MAIN_TABLE = 'divante_walkthechat_api_log';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, \Divante\Walkthechat\Api\Data\ApiLogInterface::ENTITY_ID_FIELD);
    }
}
