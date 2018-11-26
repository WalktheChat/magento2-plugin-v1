<?php
/**
 * @package   Divante\Walkthechat
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
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
     * ApiLogRepository constructor.
     *
     * @param \Divante\Walkthechat\Model\ResourceModel\ApiLog $logResource
     */
    public function __construct(
        \Divante\Walkthechat\Model\ResourceModel\ApiLog $logResource
    ) {
        $this->logResource = $logResource;
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
}
