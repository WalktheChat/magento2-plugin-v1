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
 * Class Delete
 *
 * @package Divante\Walkthechat\Controller\Adminhtml\Product
 */
class Delete extends \Magento\Backend\App\Action
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
     * @var \Divante\Walkthechat\Helper\Data
     */
    protected $helper;

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
     * @param \Divante\Walkthechat\Helper\Data                               $helper
     * @param \Divante\Walkthechat\Model\QueueService                        $queueService
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \Divante\Walkthechat\Api\Data\QueueInterfaceFactory $queueFactory,
        \Divante\Walkthechat\Model\QueueRepository $queueRepository,
        \Divante\Walkthechat\Helper\Data $helper,
        \Divante\Walkthechat\Model\QueueService $queueService
    ) {
        $this->filter            = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->queueFactory      = $queueFactory;
        $this->queueRepository   = $queueRepository;
        $this->helper            = $helper;
        $this->queueService      = $queueService;

        parent::__construct($context);
    }

    /**
     * Delete selected products from Walkthechat
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->filter->getCollection(
            $this->collectionFactory->create()->addAttributeToSelect(\Divante\Walkthechat\Helper\Data::ATTRIBUTE_CODE)
        );

        $count = 0;

        /** @var \Magento\Catalog\Api\Data\ProductInterface $product */
        foreach ($collection->getItems() as $product) {
            $walkTheChatId = $this->helper->getWalkTheChatAttributeValue($product);

            if (
                $walkTheChatId
                && !$this->queueService->isDuplicate(
                    $walkTheChatId,
                    \Divante\Walkthechat\Model\Action\Delete::ACTION,
                    \Divante\Walkthechat\Helper\Data::ATTRIBUTE_CODE
                )
            ) {
                /** @var \Divante\Walkthechat\Api\Data\QueueInterface $model */
                $model = $this->queueFactory->create();

                $model->setProductId($product->getId());
                $model->setWalkthechatId($walkTheChatId);
                $model->setAction(\Divante\Walkthechat\Model\Action\Delete::ACTION);

                $this->queueRepository->save($model);

                $count++;
            }
        }

        $this->messageManager->addSuccessMessage(__('%1 product(s) added to queue.', $count));

        $this->_redirect('catalog/product/index');
    }
}
