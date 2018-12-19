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
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * ProcessQueue constructor.
     *
     * @param \Magento\Framework\App\State            $state
     * @param \Divante\Walkthechat\Model\QueueService $queueService
     * @param \Magento\Framework\Registry             $registry
     */
    public function __construct(
        \Magento\Framework\App\State $state,
        \Divante\Walkthechat\Model\QueueService $queueService,
        \Magento\Framework\Registry $registry
    ) {
        $this->state        = $state;
        $this->queueService = $queueService;
        $this->registry     = $registry;
    }

    /**
     * Process items from queue
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        try {
            $this->state->getAreaCode();
        } catch (\Magento\Framework\Exception\LocalizedException $exception) {
            $this->state->setAreaCode('frontend');
        }

        $this->registry->register('omit_product_update_action', true);

        $items = $this->queueService->getAllNotProcessed();

        foreach ($items as $item) {
            $this->queueService->sync($item);
        }
    }
}
