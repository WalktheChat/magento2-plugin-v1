<?php
/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\Walkthechat\Observer;

/**
 * Class CatalogProductDeleteBefore
 *
 * @package Divante\Walkthechat\Observer
 */
class CatalogProductDeleteBefore implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Divante\Walkthechat\Api\Data\QueueInterfaceFactory
     */
    protected $queueFactory;

    /**
     * @var \Divante\Walkthechat\Model\QueueRepository
     */
    protected $queueRepository;

    /**
     * @var \Divante\Walkthechat\Helper\Data
     */
    protected $helper;

    /**
     * @var \Divante\Walkthechat\Model\QueueService
     */
    protected $queueService;

    /**
     * CatalogProductSaveAfter constructor.
     *
     * @param \Divante\Walkthechat\Api\Data\QueueInterfaceFactory $queueFactory
     * @param \Divante\Walkthechat\Model\QueueRepository          $queueRepository
     * @param \Divante\Walkthechat\Helper\Data                    $helper
     * @param \Divante\Walkthechat\Model\QueueService             $queueService
     */
    public function __construct(
        \Divante\Walkthechat\Api\Data\QueueInterfaceFactory $queueFactory,
        \Divante\Walkthechat\Model\QueueRepository $queueRepository,
        \Divante\Walkthechat\Helper\Data $helper,
        \Divante\Walkthechat\Model\QueueService $queueService
    ) {
        $this->queueFactory    = $queueFactory;
        $this->queueRepository = $queueRepository;
        $this->helper          = $helper;
        $this->queueService    = $queueService;
    }

    /**
     * Add item to queue once product is deleted
     *
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->helper->isEnabledProductSync()) {
            $product = $observer->getProduct();

            if ($product instanceof \Magento\Catalog\Model\Product) {
                $walkTheChatId = $this->helper->getWalkTheChatAttributeValue($product);

                if (
                    $walkTheChatId
                    && !$this->queueService->isDuplicate(
                        $walkTheChatId,
                        \Divante\Walkthechat\Model\Action\Delete::ACTION,
                        \Divante\Walkthechat\Helper\Data::ATTRIBUTE_CODE
                    )
                ) {
                    /** @var \Divante\Walkthechat\Api\Data\QueueInterface $model */
                    $model = $this->queueFactory->create();

                    $model->setWalkthechatId($walkTheChatId);
                    $model->setAction(\Divante\Walkthechat\Model\Action\Delete::ACTION);

                    $this->queueRepository->save($model);
                }
            }
        }
    }
}
