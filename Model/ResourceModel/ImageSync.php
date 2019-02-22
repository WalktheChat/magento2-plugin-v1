<?php
/**
 * @package   Divante\Walkthechat
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Model\ResourceModel;

/**
 * Class ImageSync
 *
 * @package Divante\Walkthechat\Model\ResourceModel
 */
class ImageSync extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Table name
     */
    const TABLE_NAME = 'divante_walkthechat_image_sync';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, \Divante\Walkthechat\Api\Data\ImageSyncInterface::ID);
    }
}
