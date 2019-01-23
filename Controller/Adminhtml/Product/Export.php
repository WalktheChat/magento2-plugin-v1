<?php
/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\Walkthechat\Controller\Adminhtml\Product;

/**
 * Class Export
 *
 * @package Divante\Walkthechat\Controller\Adminhtml\Product
 */
class Export extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Divante\Walkthechat\Api\Data\QueueInterfaceFactory
     */
    protected $queueFactory;

    /**
     * @var \Divante\Walkthechat\Model\QueueRepository
     */
    protected $queueRepository;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Divante\Walkthechat\Model\QueueService
     */
    protected $queueService;

    /**
     * {@inheritdoc}
     *
     * @param \Magento\Ui\Component\MassAction\Filter                        $filter
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
     * @param \Divante\Walkthechat\Api\Data\QueueInterfaceFactory            $queueFactory
     * @param \Divante\Walkthechat\Model\QueueRepository                     $queueRepository
     * @param \Magento\Catalog\Api\ProductRepositoryInterface                $productRepository
     * @param \Divante\Walkthechat\Model\QueueService                        $queueService
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \Divante\Walkthechat\Api\Data\QueueInterfaceFactory $queueFactory,
        \Divante\Walkthechat\Model\QueueRepository $queueRepository,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Divante\Walkthechat\Model\QueueService $queueService
    ) {
        $this->filter            = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->queueFactory      = $queueFactory;
        $this->queueRepository   = $queueRepository;
        $this->productRepository = $productRepository;
        $this->queueService      = $queueService;

        parent::__construct($context);
    }

    /**
     * Export selected products to Walkthechat
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->filter->getCollection($this->collectionFactory->create());

        $count           = 0;
        $productsProceed = 0;

        foreach ($collection->getAllIds() as $id) {
            $product = $this->productRepository->getById($id);

            $isSupportedProductType = in_array($product->getTypeId(), [
                \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
                \Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL,
                \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE,
            ]);

            if (!$isSupportedProductType) {
                ++$productsProceed;

                continue;
            }

            // don't add to queue twice when exporting
            if (!$this->queueService->isDuplicate(
                    $id,
                    \Divante\Walkthechat\Model\Action\Add::ACTION,
                    'product_id'
                )
            ) {
                /** @var \Divante\Walkthechat\Api\Data\QueueInterface $model */
                $model = $this->queueFactory->create();

                $model->setProductId($id);
                $model->setAction(\Divante\Walkthechat\Model\Action\Add::ACTION);

                $this->queueRepository->save($model);

                $count++;
            }
        }

        $this->messageManager->addSuccessMessage(__('%1 product(s) added to queue.', $count));

        if ($productsProceed) {
            $this->messageManager->addWarningMessage(
                __(
                    '%1 product(s) can not be exported. Supported product types: Simple, Virtual and Configurable',
                    $productsProceed
                )
            );
        }

        $this->_redirect('catalog/product/index');
    }
}
