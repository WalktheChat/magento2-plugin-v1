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
 * Class ApiLogRepository
 *
 * @package Divante\Walkthechat\Model
 */
class ApiLogRepository implements \Divante\Walkthechat\Api\ApiLogRepositoryInterface
{
    /**
     * @var \Divante\Walkthechat\Model\ResourceModel\ApiLog
     */
    protected $logResource;

    /**
     * @var \Divante\Walkthechat\Api\Data\ApiLogInterfaceFactory
     */
    protected $logFactory;

    /**
     * ApiLogRepository constructor.
     *
     * @param \Divante\Walkthechat\Model\ResourceModel\ApiLog      $logResource
     * @param \Divante\Walkthechat\Api\Data\ApiLogInterfaceFactory $logFactory
     */
    public function __construct(
        \Divante\Walkthechat\Model\ResourceModel\ApiLog $logResource,
        \Divante\Walkthechat\Api\Data\ApiLogInterfaceFactory $logFactory
    ) {
        $this->logResource = $logResource;
        $this->logFactory  = $logFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function save(\Divante\Walkthechat\Api\Data\ApiLogInterface $log)
    {
        try {
            $this->logResource->save($log);
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__($exception->getMessage()));
        }

        return $log;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($id)
    {
        /** @var \Divante\Walkthechat\Api\Data\ApiLogInterface $log */
        $log = $this->logFactory->create();

        $this->logResource->load($log, $id);

        if (!$log->getId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __('API log with id "%1" does not exist.', $log->getId())
            );
        }

        return $log;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastByQuoteItemId($id)
    {
        /** @var \Divante\Walkthechat\Api\Data\ApiLogInterface $log */
        $log = $this->logFactory->create();

        // request was rewrite in resource module to set DESC order by processed_at field
        $this->logResource->load($log, $id, \Divante\Walkthechat\Api\Data\ApiLogInterface::QUEUE_ITEM_ID_FIELD);

        return $log;
    }
}
