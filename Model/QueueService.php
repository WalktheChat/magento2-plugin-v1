<?php
/**
 * @package   Divante\Walkthechat
 *            
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Model;

/**
 * Class QueueService
 *
 * @package Divante\Walkthechat\Model
 */
class QueueService
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var \Divante\Walkthechat\Api\QueueRepositoryInterface
     */
    protected $queueRepository;

    /**
     * @var \Divante\Walkthechat\Model\ActionFactory
     */
    protected $actionFactory;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * QueueService constructor.
     *
     * @param \Magento\Framework\Stdlib\DateTime\DateTime           $date
     * @param \Divante\Walkthechat\Api\QueueRepositoryInterface     $queueRepository
     * @param \Divante\Walkthechat\Model\ActionFactory              $actionFactory
     * @param \Magento\Framework\Api\SearchCriteriaBuilder          $searchCriteriaBuilder
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Divante\Walkthechat\Api\QueueRepositoryInterface $queueRepository,
        \Divante\Walkthechat\Model\ActionFactory $actionFactory,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->date                  = $date;
        $this->queueRepository       = $queueRepository;
        $this->actionFactory         = $actionFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Get all not processed rows
     *
     * @return \Divante\Walkthechat\Api\Data\QueueInterface[]
     */
    public function getAllNotProcessed()
    {
        $this->searchCriteriaBuilder->addFilter('processed_at', true, 'null');

        /** @var \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria */
        $searchCriteria = $this->searchCriteriaBuilder->create();

        $results = $this->queueRepository->getList($searchCriteria);

        return $results->getItems();
    }

    /**
     * @param int|string $id
     * @param string $action
     * @param string $idField
     *
     * @return bool
     */
    public function isDuplicate($id, $action, $idField)
    {
        $this->searchCriteriaBuilder->addFilter('action', $action);
        $this->searchCriteriaBuilder->addFilter($idField, $id);
        $this->searchCriteriaBuilder->addFilter('processed_at', true, 'null');

        /** @var \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria */
        $searchCriteria = $this->searchCriteriaBuilder->create();

        $results = $this->queueRepository->getList($searchCriteria);

        return (bool)$results->getItems();
    }

    /**
     * Sync item with Walkthechat
     *
     * @param \Divante\Walkthechat\Api\Data\QueueInterface $item
     *
     * @throws \Exception
     */
    public function sync(\Divante\Walkthechat\Api\Data\QueueInterface $item)
    {
        $action = $this->actionFactory->create($item->getAction());

        $isSuccess = $action->execute($item);

        if ($isSuccess) {
            $item->setProcessedAt($this->date->gmtDate());

            $this->queueRepository->save($item);
        }
    }
}
