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
 * Class DeleteAll
 *
 * @package Divante\Walkthechat\Controller\Adminhtml\Product
 */
class DeleteAll extends \Magento\Backend\App\Action
{
    /**
     * @var \Divante\Walkthechat\Service\ProductsRepository
     */
    protected $queueProductRepository;

    /**
     * @var \Divante\Walkthechat\Model\QueueService
     */
    protected $queueService;

    /**
     * @var \Divante\Walkthechat\Api\Data\QueueInterfaceFactory
     */
    protected $queueFactory;

    /**
     * @var \Divante\Walkthechat\Api\QueueRepositoryInterface
     */
    protected $queueRepository;

    /**
     * {@inheritdoc}
     *
     * @param \Divante\Walkthechat\Service\ProductsRepository     $productsRepository
     * @param \Divante\Walkthechat\Model\QueueService             $queueService
     * @param \Divante\Walkthechat\Api\Data\QueueInterfaceFactory $queueFactory
     * @param \Divante\Walkthechat\Api\QueueRepositoryInterface   $queueRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Divante\Walkthechat\Service\ProductsRepository $productsRepository,
        \Divante\Walkthechat\Model\QueueService $queueService,
        \Divante\Walkthechat\Api\Data\QueueInterfaceFactory $queueFactory,
        \Divante\Walkthechat\Api\QueueRepositoryInterface $queueRepository
    ) {
        $this->queueProductRepository = $productsRepository;
        $this->queueService           = $queueService;
        $this->queueFactory           = $queueFactory;
        $this->queueRepository        = $queueRepository;

        parent::__construct($context);
    }

    /**
     * Delete all existing products from Walkthechat
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        try {
            $result = $this->queueProductRepository->find();
            $count  = 0;

            foreach ($result as $row) {
                if (
                    isset($row['id'])
                    && !$this->queueService->isDuplicate(
                        $row['id'],
                        \Divante\Walkthechat\Model\Action\Delete::ACTION,
                        \Divante\Walkthechat\Helper\Data::ATTRIBUTE_CODE
                    )
                ) {
                    /** @var \Divante\Walkthechat\Api\Data\QueueInterface $model */
                    $model = $this->queueFactory->create();

                    $model->setWalkthechatId($row['id']);
                    $model->setAction(\Divante\Walkthechat\Model\Action\Delete::ACTION);

                    $this->queueRepository->save($model);

                    $count++;
                }
            }

            $this->messageManager->addSuccessMessage(__('%1 product(s) added to queue.', $count));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $this->_redirect('*/dashboard/index');
    }
}
