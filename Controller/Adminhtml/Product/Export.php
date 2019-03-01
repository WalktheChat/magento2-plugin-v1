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
     * @param \Magento\Ui\Component\MassAction\Filter                        $filter
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
     * @param \Divante\Walkthechat\Model\ProductService                      $productService
     * @param \Psr\Log\LoggerInterface                                       $logger
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \Divante\Walkthechat\Model\ProductService $productService,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->filter            = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->productService    = $productService;
        $this->logger            = $logger;

        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     *
     * Export selected products to Walkthechat
     */
    public function execute()
    {
        try {
            /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $bulkData   = $this->productService->processProductsExport($collection->getItems());

            $this->messageManager->addSuccessMessage(__('%1 product(s) added to queue.', count($bulkData)));
        } catch (\Magento\Framework\Exception\LocalizedException $localizedException) {
            $this->messageManager->addErrorMessage(__($localizedException->getMessage()));
        } catch (\Exception $exception) {
            $this->logger->critical($exception);

            $this->messageManager->addErrorMessage(__('Internal error occurred. Please see logs or contact administrator.'));
        }

        $this->_redirect('catalog/product/index');
    }
}
