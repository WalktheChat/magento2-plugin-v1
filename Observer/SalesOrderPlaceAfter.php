<?php

namespace Divante\Walkthechat\Observer;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */
class SalesOrderPlaceAfter implements \Magento\Framework\Event\ObserverInterface
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
     *
     * @param \Divante\Walkthechat\Model\QueueFactory    $queueFactory
     * @param \Divante\Walkthechat\Model\QueueRepository $queueRepository
     * @param \Divante\Walkthechat\Helper\Data           $helper
     */
    public function __construct(
        \Divante\Walkthechat\Model\QueueFactory $queueFactory,
        \Divante\Walkthechat\Model\QueueRepository $queueRepository,
        \Divante\Walkthechat\Helper\Data $helper
    ) {
        $this->queueFactory    = $queueFactory;
        $this->queueRepository = $queueRepository;
        $this->helper          = $helper;
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

            if ($order instanceof \Magento\Sales\Model\Order) {
                foreach ($order->getAllItems() as $item) {
                    $product = $item->getProduct();

                    if ($product->getWalkthechatId()) {
                        $model = $this->queueFactory->create();
                        $model->setProductId($product->getId());
                        $model->setAction('update');
                        $this->queueRepository->save($model);
                    }
                }
            }
        }
    }
}
