<?php

namespace Divante\Walkthechat\Controller\Adminhtml\Product;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
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
     * ExportAll constructor.
     *
     * @param \Magento\Backend\App\Action\Context       $context
     * @param \Divante\Walkthechat\Model\ProductService $productService
     * @param \Divante\Walkthechat\Model\QueueService   $queueService
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Divante\Walkthechat\Model\ProductService $productService,
        \Divante\Walkthechat\Model\QueueService $queueService
    ) {
        parent::__construct($context);
        $this->productService = $productService;
        $this->queueService   = $queueService;
    }

    /**
     * Export all possible products to Walkthechat
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function execute()
    {
        /**
         * @var \Magento\Catalog\Api\Data\ProductInterface[]
         */
        $products = $this->productService->getAllForExport();

        foreach ($products as $product) {
            // temporary solution (null filter doesn't work)
            if (!$product->getWalkthechatId()) {
                $data = [
                    'product_id' => $product->getId(),
                    'action'     => 'add',
                ];

                $this->queueService->create($data);
            }
        }

        $this->messageManager->addSuccessMessage(__('Added to queue.'));

        $this->_redirect('*/dashboard/index');
    }
}
