<?php

namespace Divante\Walkthechat\Model;

use Magento\Framework\Model\AbstractModel;
use Divante\Walkthechat\Api\Data\QueueInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */
class Queue extends AbstractModel implements QueueInterface, IdentityInterface
{
    /**
     *
     */
    const CACHE_TAG = 'divante_walkthechat_queue_grid';

    /**
     * @var string
     */
    protected $_cacheTag = 'divante_walkthechat_queue_grid';

    /**
     * @var string
     */
    protected $_eventPrefix = 'divante_walkthechat_queue_grid';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Divante\Walkthechat\Model\ResourceModel\Queue::class);
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG.'_'.$this->getId()];
    }
}
