<?php
namespace Divante\Walkthechat\Observer;

/**
 * Walkthechat Observer
 *
 * @package  Divante\Walkthechat\Service
 * @author   Divante Tech Team <tech@divante.pl>
 */
class SalesOrderSaveAfter implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Divante\Walkthechat\Model\QueueFactory
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
     * CatalogProductSaveAfter constructor.
     * @param \Divante\Walkthechat\Model\QueueFactory $queueFactory
     * @param \Divante\Walkthechat\Model\QueueRepository $queueRepository
     * @param \Divante\Walkthechat\Helper\Data $helper
     */
    public function __construct(
        \Divante\Walkthechat\Model\QueueFactory $queueFactory,
        \Divante\Walkthechat\Model\QueueRepository $queueRepository,
        \Divante\Walkthechat\Helper\Data $helper
    ) {
        $this->queueFactory = $queueFactory;
        $this->queueRepository = $queueRepository;
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->helper->isEnabledOrderSync()) {
            $order = $observer->getEvent()->getOrder();

            if ($order->getWalkthechatId()) {
                $model = $this->queueFactory->create();
                $model->setOrderId($order->getId());
                $model->setAction('update');
                $this->queueRepository->save($model);
            }
        }
    }
}