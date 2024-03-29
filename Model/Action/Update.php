<?php
/**
 * @package   Divante\Walkthechat
 * @author    Alex Yeremenko <madonzy13@gmail.com>
 * @copyright 2019 WalktheChat
 * @license   See LICENSE.txt for license details.
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
     * @var \Divante\Walkthechat\Model\OrderService
     */
    protected $orderService;

    /**
     * @var \Divante\Walkthechat\Model\ProductService
     */
    protected $productService;

    /**
     * @var \Divante\Walkthechat\Helper\Data
     */
    protected $helper;

    /**
     * {@inheritdoc}
     *
     * @param \Magento\Catalog\Model\ProductRepository        $productRepository
     * @param \Magento\Sales\Model\OrderRepository            $orderRepository
     * @param \Divante\Walkthechat\Service\ProductsRepository $queueProductRepository
     * @param \Divante\Walkthechat\Service\OrdersRepository   $queueOrderRepository
     * @param \Divante\Walkthechat\Model\ImageService         $imageService
     * @param \Divante\Walkthechat\Model\OrderService         $orderService
     * @param \Divante\Walkthechat\Model\ProductService       $productService
     * @param \Divante\Walkthechat\Helper\Data                $helper
     */
    public function __construct(
        \Divante\Walkthechat\Api\Data\ImageSyncInterfaceFactory $imageSyncFactory,
        \Divante\Walkthechat\Api\ImageSyncRepositoryInterface $imageSyncRepository,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Sales\Model\OrderRepository $orderRepository,
        \Divante\Walkthechat\Service\ProductsRepository $queueProductRepository,
        \Divante\Walkthechat\Service\OrdersRepository $queueOrderRepository,
        \Divante\Walkthechat\Model\ImageService $imageService,
        \Divante\Walkthechat\Model\OrderService $orderService,
        \Divante\Walkthechat\Model\ProductService $productService,
        \Divante\Walkthechat\Helper\Data $helper
    ) {
        $this->productRepository      = $productRepository;
        $this->orderRepository        = $orderRepository;
        $this->queueProductRepository = $queueProductRepository;
        $this->queueOrderRepository   = $queueOrderRepository;
        $this->imageService           = $imageService;
        $this->orderService           = $orderService;
        $this->productService         = $productService;
        $this->helper                 = $helper;

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
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function execute(\Divante\Walkthechat\Api\Data\QueueInterface $queueItem)
    {
        if ($queueItem->getProductId()) {
            $product    = $this->productRepository->getById($queueItem->getProductId());
            $imagesData = $this->imageService->updateImages($product);
            $data       = $this->productService->prepareProductData($product, false, $imagesData);

            $data['id'] = $queueItem->getWalkthechatId() ?? $this->helper->getWalkTheChatAttributeValue($product);

            $this->queueProductRepository->update($data);

            if (isset($imagesData['_syncImageData']) && $imagesData['_syncImageData']) {
                $this->saveImagesToSyncTable($imagesData['_syncImageData']);
            }
        } elseif ($queueItem->getOrderId()) {
            $order = $this->orderRepository->get($queueItem->getOrderId());
            $data  = $this->orderService->prepareOrderData($order);

            $this->queueOrderRepository->update($data);
        }

        return true;
    }
}
