<?php
namespace Divante\Walkthechat\Controller\Adminhtml\Product;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
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
     * @var \Divante\Walkthechat\Model\QueueFactory
     */
    protected $queueFactory;

    /**
     * @var \Divante\Walkthechat\Model\QueueRepository
     */
    protected $queueRepository;

    /**
     * Export constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
     * @param \Divante\Walkthechat\Model\QueueFactory $queueFactory
     * @param \Divante\Walkthechat\Model\QueueRepository $queueRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \Divante\Walkthechat\Model\QueueFactory $queueFactory,
        \Divante\Walkthechat\Model\QueueRepository $queueRepository
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->queueFactory = $queueFactory;
        $this->queueRepository = $queueRepository;
    }

    public function execute()
    {
        /** @var Collection $collection */
        $collection = $this->filter->getCollection($this->collectionFactory->create());

        foreach ($collection->getAllIds() as $id) {
            $model = $this->queueFactory->create();
            $model->setProductId($id);
            $model->setAction('add');
            $this->queueRepository->save($model);
        }

        $this->messageManager->addSuccessMessage(__('Added to queue.'));

        $this->_redirect('catalog/product/index');
    }
}
