<?php
namespace Divante\Walkthechat\Model;

use Magento\Framework\Model\AbstractModel;
use Divante\Walkthechat\Api\Data\QueueInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Walkthechat Queue Model
 *
 * @package  Divante\Walkthechat
 * @author   Divante Tech Team <tech@divante.pl>
 */
class Queue extends AbstractModel implements QueueInterface, IdentityInterface
{
    const CACHE_TAG = 'walkthechat_queue_grid';

    /**
     * @var string
     */
    protected $_cacheTag = 'walkthechat_queue_grid';

    /**
     * @var string
     */
    protected $_eventPrefix = 'walkthechat_queue_grid';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Divante\Walkthechat\Model\ResourceModel\Queue');
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}