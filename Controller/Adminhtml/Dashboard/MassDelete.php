<?php
namespace Divante\Walkthechat\Controller\Adminhtml\Dashboard;

/**
 * Walkthechat Mass Queue Delete
 *
 * @package  Divante\Walkthechat
 * @author   Divante Tech Team <tech@divante.pl>
 */
class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    /**
     * @var \Divante\Walkthechat\Model\ResourceModel\Queue\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Divante\Walkthechat\Model\QueueRepository
     */
    protected $queueRepository;

    /**
     * MassDelete constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Divante\Walkthechat\Model\ResourceModel\Queue\CollectionFactory $collectionFactory
     * @param \Divante\Walkthechat\Model\QueueRepository $queueRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Divante\Walkthechat\Model\ResourceModel\Queue\CollectionFactory $collectionFactory,
        \Divante\Walkthechat\Model\QueueRepository $queueRepository
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->queueRepository = $queueRepository;
    }

    public function execute()
    {
        /** @var Collection $collection */
        $collection = $this->filter->getCollection($this->collectionFactory->create());

        foreach ($collection as $row) {
            $this->queueRepository->delete($row);
        }

        $this->messageManager->addSuccessMessage(__('Deleted items.'));

        $this->_redirect('*/*/index');
    }
}
