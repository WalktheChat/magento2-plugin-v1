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
 * Class SalesOrderSaveAfter
 *
 * @package Divante\Walkthechat\Observer
 */
class SalesOrderSaveAfter implements \Magento\Framework\Event\ObserverInterface
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
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * CatalogProductSaveAfter constructor.
     *
     * @param \Divante\Walkthechat\Api\Data\QueueInterfaceFactory $queueFactory
     * @param \Divante\Walkthechat\Model\QueueRepository          $queueRepository
     * @param \Divante\Walkthechat\Helper\Data                    $helper
     * @param \Divante\Walkthechat\Model\QueueService             $queueService
     * @param \Magento\Framework\Registry                         $registry
     * @param \Magento\Sales\Api\OrderRepositoryInterface         $orderRepository
     */
    public function __construct(
        \Divante\Walkthechat\Api\Data\QueueInterfaceFactory $queueFactory,
        \Divante\Walkthechat\Model\QueueRepository $queueRepository,
        \Divante\Walkthechat\Helper\Data $helper,
        \Divante\Walkthechat\Model\QueueService $queueService,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
    ) {
        $this->queueFactory    = $queueFactory;
        $this->queueRepository = $queueRepository;
        $this->helper          = $helper;
        $this->queueService    = $queueService;
        $this->registry        = $registry;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Add item to queue once order is updated
     *
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->helper->isEnabledOrderSync()) {
            $order = $observer->getEvent()->getOrder();

            // is shipment was called then fetch order from shipment instance
            if (!$order instanceof \Magento\Sales\Api\Data\OrderInterface) {
                $shipment = $observer->getEvent()->getShipment();

                if ($shipment instanceof \Magento\Sales\Api\Data\ShipmentInterface) {
                    $order = $this->orderRepository->get($shipment->getOrderId());
                }
            }

            if (
                $order instanceof \Magento\Sales\Api\Data\OrderInterface
                && !$this->registry->registry('walkthechat_omit_update_action')
                && $order->getWalkthechatId()
                && !$this->queueService->isDuplicate(
                    $order->getEntityId(),
                    \Divante\Walkthechat\Model\Action\Update::ACTION,
                    'order_id'
                )
            ) {
                /** @var \Divante\Walkthechat\Api\Data\QueueInterface $model */
                $model = $this->queueFactory->create();

                $model->setOrderId($order->getEntityId());
                $model->setAction(\Divante\Walkthechat\Model\Action\Update::ACTION);

                $this->queueRepository->save($model);
            }
        }
    }
}
