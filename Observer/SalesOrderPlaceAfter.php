<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Observer;

/**
 * Class SalesOrderPlaceAfter
 *
 * @package Divante\Walkthechat\Observer
 */
class SalesOrderPlaceAfter implements \Magento\Framework\Event\ObserverInterface
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
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * CatalogProductSaveAfter constructor.
     *
     * @param \Divante\Walkthechat\Api\Data\QueueInterfaceFactory $queueFactory
     * @param \Divante\Walkthechat\Model\QueueRepository          $queueRepository
     * @param \Divante\Walkthechat\Helper\Data                    $helper
     * @param \Divante\Walkthechat\Model\QueueService             $queueService
     * @param \Magento\Framework\Registry                         $registry
     */
    public function __construct(
        \Divante\Walkthechat\Api\Data\QueueInterfaceFactory $queueFactory,
        \Divante\Walkthechat\Model\QueueRepository $queueRepository,
        \Divante\Walkthechat\Helper\Data $helper,
        \Divante\Walkthechat\Model\QueueService $queueService,
        \Magento\Framework\Registry $registry
    ) {
        $this->queueFactory    = $queueFactory;
        $this->queueRepository = $queueRepository;
        $this->helper          = $helper;
        $this->queueService    = $queueService;
        $this->registry        = $registry;
    }

    /**
     * Add item to queue once order is placed
     *
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->helper->isEnabledOrderSync()) {
            $order = $observer->getEvent()->getOrder();

            if (
                $order instanceof \Magento\Sales\Api\Data\OrderInterface
                && !$this->registry->registry('walkthechat_omit_update_action')
                && !$this->queueService->isDuplicate(
                    $order->getId(),
                    \Divante\Walkthechat\Model\Action\Update::ACTION,
                    'order_id'
                )
            ) {
                foreach ($order->getAllItems() as $item) {
                    $product       = $item->getProduct();
                    $walkTheChatId = $this->helper->getWalkTheChatAttributeValue($product);

                    if (
                        $walkTheChatId
                        && !$this->queueService->isDuplicate(
                            $product->getId(),
                            \Divante\Walkthechat\Model\Action\Update::ACTION,
                            'product_id'
                        )
                    ) {
                        /** @var \Divante\Walkthechat\Api\Data\QueueInterface $model */
                        $model = $this->queueFactory->create();

                        $model->setProductId($product->getId());
                        $model->setWalkthechatId($walkTheChatId);
                        $model->setAction(\Divante\Walkthechat\Model\Action\Update::ACTION);

                        $this->queueRepository->save($model);
                    }
                }
            }
        }
    }
}
