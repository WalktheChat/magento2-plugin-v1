<?php
namespace Divante\Walkthechat\Controller\Adminhtml\Product;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */
class DeleteAll extends \Magento\Backend\App\Action
{
    /**
     * @var \Divante\Walkthechat\Service\ProductsRepository
     */
    protected $productsRepository;

    /**
     * @var \Divante\Walkthechat\Model\QueueService
     */
    protected $queueService;

    /**
     * DeleteAll constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Divante\Walkthechat\Service\ProductsRepository $productsRepository
     * @param \Divante\Walkthechat\Model\QueueService $queueService
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Divante\Walkthechat\Service\ProductsRepository $productsRepository,
        \Divante\Walkthechat\Model\QueueService $queueService
    )
    {
        parent::__construct($context);
        $this->productsRepository = $productsRepository;
        $this->queueService = $queueService;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        try {
            $result = $this->productsRepository->find();

            foreach ($result as $row) {
                if (isset($row['id'])) {
                    $data = [
                        'walkthechat_id' => $row['id'],
                        'action' => 'delete'
                    ];

                    $this->queueService->create($data);
                }
            }

            $this->messageManager->addSuccessMessage(__('Added to queue.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $this->_redirect('*/dashboard/index');
    }
}