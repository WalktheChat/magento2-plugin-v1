<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Controller\Adminhtml\Dashboard;

/**
 * Class MassDelete
 *
 * @package Divante\Walkthechat\Controller\Adminhtml\Dashboard
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
     * {@inheritdoc}
     *
     * @param \Magento\Ui\Component\MassAction\Filter                          $filter
     * @param \Divante\Walkthechat\Model\ResourceModel\Queue\CollectionFactory $collectionFactory
     * @param \Divante\Walkthechat\Model\QueueRepository                       $queueRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Divante\Walkthechat\Model\ResourceModel\Queue\CollectionFactory $collectionFactory,
        \Divante\Walkthechat\Model\QueueRepository $queueRepository
    ) {
        $this->filter            = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->queueRepository   = $queueRepository;

        parent::__construct($context);
    }

    /**
     * Delete selected items from queue
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        /** @var \Divante\Walkthechat\Model\ResourceModel\Queue\Collection $collection */
        $collection = $this->filter->getCollection($this->collectionFactory->create());

        $count = 0;

        foreach ($collection as $item) {
            $this->queueRepository->delete($item);

            $count++;
        }

        $this->messageManager->addSuccessMessage(__('%1 item(s) deleted.', $count));

        $this->_redirect('*/*/index');
    }
}
