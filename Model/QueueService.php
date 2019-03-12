<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Alex Yeremenko <madonzy13@gmail.com>
 * @copyright 2019 WalktheChat
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
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * QueueService constructor.
     *
     * @param \Magento\Framework\Stdlib\DateTime\DateTime       $date
     * @param \Divante\Walkthechat\Api\QueueRepositoryInterface $queueRepository
     * @param \Divante\Walkthechat\Model\ActionFactory          $actionFactory
     * @param \Magento\Framework\Api\SearchCriteriaBuilder      $searchCriteriaBuilder
     * @param \Psr\Log\LoggerInterface                          $logger
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Divante\Walkthechat\Api\QueueRepositoryInterface $queueRepository,
        \Divante\Walkthechat\Model\ActionFactory $actionFactory,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->date                  = $date;
        $this->queueRepository       = $queueRepository;
        $this->actionFactory         = $actionFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->logger                = $logger;
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
     * Check if has duplicated items
     *
     * @param int|string $id
     * @param string     $action
     * @param string     $idField
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

        try {
            $isSuccess = $action->execute($item);

            if ($isSuccess) {
                $item->setProcessedAt($this->date->gmtDate());
                $item->setStatus(\Divante\Walkthechat\Api\Data\QueueInterface::COMPLETE_STATUS);
            }
        } catch (\Zend\Http\Client\Exception\RuntimeException $runtimeException) {
            $item->setStatus(\Divante\Walkthechat\Api\Data\QueueInterface::API_ERROR_STATUS);

            $this->logger->error(
                "WalkTheChat | Bad response when trying to proceed the queue item with ID: #{$item->getId()}. Please check logs in admin panel (WalkTheChat -> Logs) for more details."
            );
        } catch (\Exception $exception) {
            $item->setStatus(\Divante\Walkthechat\Api\Data\QueueInterface::INTERNAL_ERROR_STATUS);

            $this->logger->critical(
                "WalkTheChat | Internal error occurred: {$exception->getMessage()}",
                $exception->getTrace()
            );
        }

        $this->queueRepository->save($item);
    }
}
