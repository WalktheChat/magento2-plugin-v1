<?php
namespace Divante\Walkthechat\Controller\Adminhtml\Product;

/**
 * Walkthechat Export All Controller
 *
 * @package  Divante\Walkthechat
 * @author   Divante Tech Team <tech@divante.pl>
 */
class ExportAll extends \Magento\Backend\App\Action
{
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
     * ExportAll constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
     * @param \Divante\Walkthechat\Model\QueueFactory $queueFactory
     * @param \Divante\Walkthechat\Model\QueueRepository $queueRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \Divante\Walkthechat\Model\QueueFactory $queueFactory,
        \Divante\Walkthechat\Model\QueueRepository $queueRepository
    )
    {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->queueFactory = $queueFactory;
        $this->queueRepository = $queueRepository;
    }

    public function execute()
    {
        $collection = $this->collectionFactory->create();
        $collection->addAttributeToFilter(
            'type_id', [
                'in' => [
                    \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
                    \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE
                ]
            ]
        );

        foreach ($collection as $product) {
            $model = $this->queueFactory->create();
            $model->setProductId($product->getId());
            $model->setAction('add');
            $this->queueRepository->save($model);
        }

        $this->messageManager->addSuccessMessage(__('Added to queue.'));

        $this->_redirect('*/dashboard/index');
    }
}