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
     * @var \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable
     */
    protected $configurableProductType;
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * CatalogProductSaveAfter constructor.
     *
     * @param \Divante\Walkthechat\Model\QueueFactory                                    $queueFactory
     * @param \Divante\Walkthechat\Model\QueueRepository                                 $queueRepository
     * @param \Divante\Walkthechat\Helper\Data                                           $helper
     * @param \Magento\Framework\Registry                                                $registry
     * @param \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $configurableProductType
     * @param \Magento\Catalog\Api\ProductRepositoryInterface                            $productRepository
     */
    public function __construct(
        \Divante\Walkthechat\Model\QueueFactory $queueFactory,
        \Divante\Walkthechat\Model\QueueRepository $queueRepository,
        \Divante\Walkthechat\Helper\Data $helper,
        \Magento\Framework\Registry $registry,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $configurableProductType,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    ) {
        $this->queueFactory            = $queueFactory;
        $this->queueRepository         = $queueRepository;
        $this->helper                  = $helper;
        $this->registry                = $registry;
        $this->configurableProductType = $configurableProductType;
        $this->productRepository       = $productRepository;
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
                $walkTheChatId = $this->getWalkTheChatAttribute($product);

                if (!$this->registry->registry('omit_product_update_action')) {
                    if ($walkTheChatId) {
                        $this->addProductToQueue($product->getId(), $walkTheChatId);
                    }

                    if (in_array($product->getTypeId(), [
                        \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
                        \Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL,
                    ])) {
                        foreach ($this->configurableProductType->getParentIdsByChild($product->getId()) as $parentId) {
                            $parent = $this->productRepository->getById($parentId);

                            $parentWalkTheChatId = $this->getWalkTheChatAttribute($parent);

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
        /** @var \Divante\Walkthechat\Model\Queue $model */
        $model = $this->queueFactory->create();

        $model->setProductId($productId);
        $model->setWalkthechatId($walkTheChatId);
        $model->setAction('update');

        $this->queueRepository->save($model);
    }

    /**
     * Return Walk the chat ID form product
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     *
     * @return string|null
     */
    protected function getWalkTheChatAttribute(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $walkTheChatIdAttribute = $product->getCustomAttribute('walkthechat_id');

        if ($walkTheChatIdAttribute instanceof \Magento\Framework\Api\AttributeValue) {
            return $walkTheChatIdAttribute->getValue();
        }

        return null;
    }
}
