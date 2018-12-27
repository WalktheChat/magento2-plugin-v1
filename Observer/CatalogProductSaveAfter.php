<?php

namespace Divante\Walkthechat\Observer;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */
class CatalogProductSaveAfter implements \Magento\Framework\Event\ObserverInterface
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
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * CatalogProductSaveAfter constructor.
     *
     * @param \Divante\Walkthechat\Model\QueueFactory    $queueFactory
     * @param \Divante\Walkthechat\Model\QueueRepository $queueRepository
     * @param \Divante\Walkthechat\Helper\Data           $helper
     * @param \Magento\Framework\Registry                $registry
     */
    public function __construct(
        \Divante\Walkthechat\Model\QueueFactory $queueFactory,
        \Divante\Walkthechat\Model\QueueRepository $queueRepository,
        \Divante\Walkthechat\Helper\Data $helper,
        \Magento\Framework\Registry $registry
    ) {
        $this->queueFactory    = $queueFactory;
        $this->queueRepository = $queueRepository;
        $this->helper          = $helper;
        $this->registry        = $registry;
    }

    /**
     * Add item to queue once product is updated
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
                $walkTheChatIdAttribute = $product->getCustomAttribute('walkthechat_id');

                if ($walkTheChatIdAttribute instanceof \Magento\Framework\Api\AttributeValue) {
                    $walkTheChatId = $walkTheChatIdAttribute->getValue();

                    if (
                        $walkTheChatId
                        && !$this->registry->registry('omit_product_update_action')
                    ) {
                        $model = $this->queueFactory->create();
                        $model->setProductId($product->getId());
                        $model->setWalkthechatId($walkTheChatId);
                        $model->setAction('update');

                        $this->queueRepository->save($model);
                    }
                }
            }
        }
    }
}
