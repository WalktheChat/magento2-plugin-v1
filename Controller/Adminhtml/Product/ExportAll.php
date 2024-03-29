<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Alex Yeremenko <madonzy13@gmail.com>
 * @copyright 2019 WalktheChat
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
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * {@inheritdoc}
     *
     * @param \Divante\Walkthechat\Model\ProductService $productService
     * @param \Psr\Log\LoggerInterface                  $logger
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Divante\Walkthechat\Model\ProductService $productService,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->productService = $productService;
        $this->logger         = $logger;

        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     *
     * Export all possible products to Walkthechat
     */
    public function execute()
    {
        try {
            /** @var \Magento\Catalog\Api\Data\ProductInterface[] */
            $products = $this->productService->getAllForExport();
            $bulkData = $this->productService->processProductsExport($products, $this->messageManager);

            $this->messageManager->addSuccessMessage(__('%1 product(s) added to queue.', count($bulkData)));
        } catch (\Magento\Framework\Exception\LocalizedException $localizedException) {
            $this->messageManager->addErrorMessage(__($localizedException->getMessage()));
        } catch (\Exception $exception) {
            $this->logger->critical($exception);

            $this->messageManager->addErrorMessage(__('Internal error occurred. Please see logs or contact administrator.'));
        }

        $this->_redirect('*/dashboard/index');
    }
}
