<?php
namespace Divante\Walkthechat\Controller\Adminhtml\Product;

/**
 * Walkthechat Delete All Controller
 *
 * @package  Divante\Walkthechat
 * @author   Divante Tech Team <tech@divante.pl>
 */
class DeleteAll extends \Magento\Backend\App\Action
{
    /**
     * @var \Divante\Walkthechat\Service\Products
     */
    protected $productsService;

    /**
     * @var \Divante\Walkthechat\Model\QueueFactory
     */
    protected $queueFactory;

    /**
     * @var \Divante\Walkthechat\Model\QueueRepository
     */
    protected $queueRepository;

    /**
     * DeleteAll constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Divante\Walkthechat\Service\Products $productsService
     * @param \Divante\Walkthechat\Model\QueueFactory $queueFactory
     * @param \Divante\Walkthechat\Model\QueueRepository $queueRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Divante\Walkthechat\Service\Products $productsService,
        \Divante\Walkthechat\Model\QueueFactory $queueFactory,
        \Divante\Walkthechat\Model\QueueRepository $queueRepository
    )
    {
        parent::__construct($context);
        $this->productsService = $productsService;
        $this->queueFactory = $queueFactory;
        $this->queueRepository = $queueRepository;
    }

    public function execute()
    {
        $result = $this->productsService->find();

        foreach ($result as $row) {
            $model = $this->queueFactory->create();
            $model->setWalkthechatId($row['id']);
            $model->setDelete('add');
            $this->queueRepository->save($model);
        }

        $this->messageManager->addSuccessMessage(__('Added to queue.'));

        $this->_redirect('*/dashboard/index');
    }
}