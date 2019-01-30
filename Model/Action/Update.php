<?php
/**
 * @package   Divante\Walkthechat
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\Walkthechat\Model\Action;

/**
 * Class Update
 *
 * @package Divante\Walkthechat\Model\Action
 */
class Update extends \Divante\Walkthechat\Model\Action\AbstractAction
{
    /**
     * Action name
     *
     * @string
     */
    const ACTION = 'update';

    /**
     * @var \Divante\Walkthechat\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \Magento\Sales\Model\OrderRepository
     */
    protected $orderRepository;

    /**
     * @var \Divante\Walkthechat\Service\ProductsRepository
     */
    protected $queueProductRepository;

    /**
     * @var \Divante\Walkthechat\Service\OrdersRepository
     */
    protected $queueOrderRepository;

    /**
     * @var \Divante\Walkthechat\Model\ImageService
     */
    protected $imageService;

    /**
     * {@inheritdoc}
     *
     * @param \Divante\Walkthechat\Helper\Data                $helper
     * @param \Magento\Catalog\Model\ProductRepository        $productRepository
     * @param \Magento\Sales\Model\OrderRepository            $orderRepository
     * @param \Divante\Walkthechat\Service\ProductsRepository $queueProductRepository
     * @param \Divante\Walkthechat\Service\OrdersRepository   $queueOrderRepository
     * @param \Divante\Walkthechat\Model\ImageService         $imageService
     */
    public function __construct(
        \Divante\Walkthechat\Api\Data\ImageSyncInterfaceFactory $imageSyncFactory,
        \Divante\Walkthechat\Api\ImageSyncRepositoryInterface $imageSyncRepository,
        \Divante\Walkthechat\Helper\Data $helper,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Sales\Model\OrderRepository $orderRepository,
        \Divante\Walkthechat\Service\ProductsRepository $queueProductRepository,
        \Divante\Walkthechat\Service\OrdersRepository $queueOrderRepository,
        \Divante\Walkthechat\Model\ImageService $imageService
    ) {
        $this->helper                 = $helper;
        $this->productRepository      = $productRepository;
        $this->orderRepository        = $orderRepository;
        $this->queueProductRepository = $queueProductRepository;
        $this->queueOrderRepository   = $queueOrderRepository;
        $this->imageService           = $imageService;

        parent::__construct(
            $imageSyncFactory,
            $imageSyncRepository
        );
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Zend_Http_Client_Exception
     * @throws \Magento\Framework\Exception\CronException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function execute(\Divante\Walkthechat\Api\Data\QueueInterface $queueItem)
    {
        if ($queueItem->getProductId()) {
            $product    = $this->productRepository->getById($queueItem->getProductId());
            $imagesData = $this->imageService->updateImages($product);
            $data       = $this->helper->prepareProductData($product, false, $imagesData);

            $data['id'] = $queueItem->getWalkthechatId();

            $this->queueProductRepository->update($data);

            if (isset($imagesData['_syncImageData']) && $imagesData['_syncImageData']) {
                $this->saveImagesToSyncTable($imagesData['_syncImageData']);
            }
        } elseif ($queueItem->getOrderId()) {
            $order = $this->orderRepository->get($queueItem->getOrderId());
            $data  = $this->helper->prepareOrderData($order);

            $this->queueOrderRepository->update($data);
        }

        return true;
    }
}
