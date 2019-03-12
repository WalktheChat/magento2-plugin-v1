<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Alex Yeremenko <madonzy13@gmail.com>
 * @copyright 2019 WalktheChat
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Observer;

/**
 * Class CatalogProductSaveAfter
 *
 * @package Divante\Walkthechat\Observer
 */
class CatalogProductSaveAfter implements \Magento\Framework\Event\ObserverInterface
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
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable
     */
    protected $configurableProductType;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Divante\Walkthechat\Model\QueueService
     */
    protected $queueService;

    /**
     * CatalogProductSaveAfter constructor.
     *
     * @param \Divante\Walkthechat\Api\Data\QueueInterfaceFactory                        $queueFactory
     * @param \Divante\Walkthechat\Model\QueueRepository                                 $queueRepository
     * @param \Divante\Walkthechat\Helper\Data                                           $helper
     * @param \Magento\Framework\Registry                                                $registry
     * @param \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $configurableProductType
     * @param \Magento\Catalog\Api\ProductRepositoryInterface                            $productRepository
     * @param \Divante\Walkthechat\Model\QueueService                                    $queueService
     */
    public function __construct(
        \Divante\Walkthechat\Api\Data\QueueInterfaceFactory $queueFactory,
        \Divante\Walkthechat\Model\QueueRepository $queueRepository,
        \Divante\Walkthechat\Helper\Data $helper,
        \Magento\Framework\Registry $registry,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $configurableProductType,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Divante\Walkthechat\Model\QueueService $queueService
    ) {
        $this->queueFactory            = $queueFactory;
        $this->queueRepository         = $queueRepository;
        $this->helper                  = $helper;
        $this->registry                = $registry;
        $this->configurableProductType = $configurableProductType;
        $this->productRepository       = $productRepository;
        $this->queueService            = $queueService;
    }

    /**
     * Add item to queue once product is updated
     *
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->helper->isEnabledProductSync()) {
            $product = $observer->getProduct();

            if ($product instanceof \Magento\Catalog\Model\Product) {
                $walkTheChatId = $this->helper->getWalkTheChatAttributeValue($product);

                if (!$this->registry->registry('walkthechat_omit_update_action')) {
                    // add main product to queue
                    if ($walkTheChatId) {
                        $this->addProductToQueue($product->getId(), $walkTheChatId);
                    }

                    // if product is a child of exported configurable product - add parent to queue
                    if (in_array($product->getTypeId(), [
                        \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
                        \Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL,
                    ])) {
                        foreach ($this->configurableProductType->getParentIdsByChild($product->getId()) as $parentId) {
                            $parent = $this->productRepository->getById($parentId);

                            $parentWalkTheChatId = $this->helper->getWalkTheChatAttributeValue($parent);

                            if ($parentWalkTheChatId) {
                                $this->addProductToQueue($parentId, $parentWalkTheChatId);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Add product to queue
     *
     * @param int $productId
     * @param int $walkTheChatId
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    protected function addProductToQueue($productId, $walkTheChatId)
    {
        if (!$this->queueService->isDuplicate(
            $productId,
            \Divante\Walkthechat\Model\Action\Update::ACTION,
            'product_id'
        )) {
            /** @var \Divante\Walkthechat\Api\Data\QueueInterface $model */
            $model = $this->queueFactory->create();

            $model->setProductId($productId);
            $model->setWalkthechatId($walkTheChatId);
            $model->setAction(\Divante\Walkthechat\Model\Action\Update::ACTION);

            $this->queueRepository->save($model);
        }
    }
}
