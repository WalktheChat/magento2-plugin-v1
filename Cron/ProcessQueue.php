<?php
namespace Divante\Walkthechat\Cron;

use Magento\Framework\App\ResourceConnection;
use Magento\Eav\Api\AttributeRepositoryInterface as AttributeRepository;
use Magento\Framework\App\Config\MutableScopeConfigInterface as ScopeConfig;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Store\Model\Store;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */
class ProcessQueue
{
    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * @var \Divante\Walkthechat\Model\QueueService
     */
    protected $queueService;

    /**
     * ProcessQueue constructor.
     * @param \Magento\Framework\App\State $state
     * @param \Divante\Walkthechat\Model\QueueService $queueService
     */
    public function __construct(
        \Magento\Framework\App\State $state,
        \Divante\Walkthechat\Model\QueueService $queueService
    ) {
        $this->state = $state;
        $this->queueService = $queueService;
    }

    public function execute()
    {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);

        $items = $this->queueService->getAllNotProcessed();

        foreach ($items as $item) {
            $this->queueService->sync($item);
        }
    }
}
