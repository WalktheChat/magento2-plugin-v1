<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Controller\Adminhtml\Product;

/**
 * Class ExportAll
 *
 * @package Divante\Walkthechat\Controller\Adminhtml\Product
 */
class ExportAll extends \Magento\Backend\App\Action
{
    /**
     * @var \Divante\Walkthechat\Model\ProductService
     */
    protected $productService;

    /**
     * @var \Divante\Walkthechat\Model\QueueService
     */
    protected $queueService;

    /**
     * @var \Divante\Walkthechat\Api\Data\QueueInterfaceFactory
     */
    protected $queueFactory;

    /**
     * @var \Divante\Walkthechat\Api\QueueRepositoryInterface
     */
    protected $queueRepository;

    /**
     * @var \Divante\Walkthechat\Helper\Data
     */
    protected $helper;

    /**
     * {@inheritdoc}
     *
     * @param \Divante\Walkthechat\Model\ProductService           $productService
     * @param \Divante\Walkthechat\Model\QueueService             $queueService
     * @param \Divante\Walkthechat\Api\Data\QueueInterfaceFactory $queueFactory
     * @param \Divante\Walkthechat\Api\QueueRepositoryInterface   $queueRepository
     * @param \Divante\Walkthechat\Helper\Data                    $helper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Divante\Walkthechat\Model\ProductService $productService,
        \Divante\Walkthechat\Model\QueueService $queueService,
        \Divante\Walkthechat\Api\Data\QueueInterfaceFactory $queueFactory,
        \Divante\Walkthechat\Api\QueueRepositoryInterface $queueRepository,
        \Divante\Walkthechat\Helper\Data $helper
    ) {
        $this->productService  = $productService;
        $this->queueService    = $queueService;
        $this->queueFactory    = $queueFactory;
        $this->queueRepository = $queueRepository;
        $this->helper          = $helper;

        parent::__construct($context);
    }

    /**
     * Export all possible products to Walkthechat
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        /**
         * @var \Magento\Catalog\Api\Data\ProductInterface[]
         */
        $products = $this->productService->getAllForExport();

        $count           = 0;
        $productsProceed = 0;

        foreach ($products as $product) {
            // temporary solution (null filter doesn't work for custom attributes)
            if (!$product->getWalkthechatId()) {
                $isSupportedProductType = in_array($product->getTypeId(), [
                    \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
                    \Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL,
                    \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE,
                ]);

                if (!$isSupportedProductType) {
                    ++$productsProceed;

                    continue;
                }

                $walkTheChatAttributeValue = $this->helper->getWalkTheChatAttributeValue($product);

                // don't add to queue twice when exporting
                if (
                    null === $walkTheChatAttributeValue
                    && !$this->queueService->isDuplicate(
                        $product->getId(),
                        \Divante\Walkthechat\Model\Action\Add::ACTION,
                        'product_id'
                    )
                ) {
                    /** @var \Divante\Walkthechat\Api\Data\QueueInterface $model */
                    $model = $this->queueFactory->create();

                    $model->setProductId($product->getId());
                    $model->setAction(\Divante\Walkthechat\Model\Action\Add::ACTION);

                    $this->queueRepository->save($model);

                    $count++;
                }
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

        $this->_redirect('*/dashboard/index');
    }
}
