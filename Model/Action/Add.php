<?php
/**
 * @package   Divante\Walkthechat
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Model\Action;

/**
 * Class Add
 *
 * @package Divante\Walkthechat\Model\Action
 */
class Add extends \Divante\Walkthechat\Model\Action\AbstractAction
{
    /**
     * Action name
     *
     * @string
     */
    const ACTION = 'add';

    /**
     * @var \Divante\Walkthechat\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \Divante\Walkthechat\Service\ProductsRepository
     */
    protected $queueProductRepository;

    /**
     * @var \Divante\Walkthechat\Service\ImagesRepository
     */
    protected $requestImagesRepository;

    /**
     * @var \Divante\Walkthechat\Model\ImageService
     */
    protected $imageService;

    /**
     * @var \Divante\Walkthechat\Model\ProductService
     */
    protected $productService;

    /**
     * {@inheritdoc}
     *
     * @param \Divante\Walkthechat\Helper\Data                $helper
     * @param \Magento\Catalog\Model\ProductRepository        $productRepository
     * @param \Divante\Walkthechat\Service\ProductsRepository $queueProductRepositoryFactory
     * @param \Divante\Walkthechat\Service\ImagesRepository   $requestImagesRepository
     * @param \Divante\Walkthechat\Model\ImageService         $imageService
     * @param \Divante\Walkthechat\Model\ProductService       $productService
     */
    public function __construct(
        \Divante\Walkthechat\Api\Data\ImageSyncInterfaceFactory $imageSyncFactory,
        \Divante\Walkthechat\Api\ImageSyncRepositoryInterface $imageSyncRepository,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Divante\Walkthechat\Service\ProductsRepository $queueProductRepositoryFactory,
        \Divante\Walkthechat\Service\ImagesRepository $requestImagesRepository,
        \Divante\Walkthechat\Model\ImageService $imageService,
        \Divante\Walkthechat\Model\ProductService $productService
    ) {
        $this->productRepository       = $productRepository;
        $this->queueProductRepository  = $queueProductRepositoryFactory;
        $this->requestImagesRepository = $requestImagesRepository;
        $this->imageService            = $imageService;
        $this->productService          = $productService;

        parent::__construct(
            $imageSyncFactory,
            $imageSyncRepository
        );
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\CronException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Zend_Http_Client_Exception
     */
    public function execute(\Divante\Walkthechat\Api\Data\QueueInterface $queueItem)
    {
        $product    = $this->productRepository->getById($queueItem->getProductId());
        $imagesData = $this->imageService->addImages($product);

        $data          = $this->productService->prepareProductData($product, true, $imagesData);
        $walkTheChatId = $this->queueProductRepository->create($data);

        if (!$walkTheChatId) {
            return false;
        }

        $product->setWalkthechatId($walkTheChatId);

        $this->productRepository->save($product);

        if ($imagesData['_syncImageData']) {
            $this->saveImagesToSyncTable($imagesData['_syncImageData']);
        }

        return true;
    }
}
