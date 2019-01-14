<?php

namespace Divante\Walkthechat\Controller\Adminhtml\Product;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
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
    private $helper;

    /**
     * Delete constructor.
     *
     * @param \Magento\Backend\App\Action\Context                            $context
     * @param \Magento\Ui\Component\MassAction\Filter                        $filter
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
     * @param \Divante\Walkthechat\Model\QueueFactory                        $queueFactory
     * @param \Divante\Walkthechat\Model\QueueRepository                     $queueRepository
     * @param \Divante\Walkthechat\Helper\Data                               $helper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \Divante\Walkthechat\Model\QueueFactory $queueFactory,
        \Divante\Walkthechat\Model\QueueRepository $queueRepository,
        \Divante\Walkthechat\Helper\Data $helper
    ) {
        parent::__construct($context);

        $this->filter            = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->queueFactory      = $queueFactory;
        $this->queueRepository   = $queueRepository;
        $this->helper            = $helper;
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
            $this->collectionFactory->create()->addAttributeToSelect('walkthechat_id')
        );

        $count = 0;

        /** @var \Magento\Catalog\Api\Data\ProductInterface $product */
        foreach ($collection->getItems() as $product) {
            $walkTheChatId = $this->helper->getWalkTheChatAttribute($product);

            if ($walkTheChatId) {
                $model = $this->queueFactory->create();
                $model->setProductId($product->getId());
                $model->setWalkthechatId($walkTheChatId);
                $model->setAction('delete');

                $this->queueRepository->save($model);

                $count++;
            }
        }

        $this->messageManager->addSuccessMessage(__('%1 product(s) added to queue.', $count));

        $this->_redirect('catalog/product/index');
    }
}
