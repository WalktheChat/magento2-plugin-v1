<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Alex Yeremenko <madonzy13@gmail.com>
 * @copyright 2019 WalktheChat
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Log;

/**
 * Class ApiLogger
 *
 * @package Divante\Walkthechat\Log
 */
class ApiLogger
{
    /**
     * @var \Divante\Walkthechat\Model\ApiLogFactory
     */
    protected $apiLogFactory;

    /**
     * @var \Divante\Walkthechat\Model\ApiLogRepository
     */
    protected $apiLogRepository;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * ApiLogger constructor.
     *
     * @param \Divante\Walkthechat\Model\ApiLogFactory    $apiLogFactory
     * @param \Divante\Walkthechat\Model\ApiLogRepository $apiLogRepository
     * @param \Psr\Log\LoggerInterface                    $logger
     * @param \Magento\Framework\Registry                 $registry
     */
    public function __construct(
        \Divante\Walkthechat\Model\ApiLogFactory $apiLogFactory,
        \Divante\Walkthechat\Model\ApiLogRepository $apiLogRepository,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Registry $registry
    ) {
        $this->apiLogFactory    = $apiLogFactory;
        $this->apiLogRepository = $apiLogRepository;
        $this->logger           = $logger;
        $this->registry         = $registry;
    }

    /**
     * Log API into database
     *
     * @param \Divante\Walkthechat\Service\Resource\AbstractResource $requestResource
     * @param array|string                                           $params
     * @param \Zend_Http_Response                                    $response
     * @param null|bool                                              $placeholderId
     */
    public function log(
        \Divante\Walkthechat\Service\Resource\AbstractResource $requestResource,
        $params,
        \Zend_Http_Response $response,
        $placeholderId
    ) {
        /** @var \Divante\Walkthechat\Api\Data\ApiLogInterface $apiLog */
        $apiLog = $this->apiLogFactory->create();

        $responseText = $response->asString();
        $path         = $requestResource->getPath();
        $queueItemId  = $this->registry->registry('walkthechat_current_queue_item_id');

        if (null !== $placeholderId) {
            $path = str_replace(':id', $placeholderId, $path);
        }

        $apiLog
            ->setRequestPath($path)
            ->setRequestParams($params)
            ->setRequestMethod($requestResource->getType())
            ->setResponseCode($response::extractCode($responseText))
            ->setResponseData(json_decode($response->getBody(), true))
            ->setIsSuccessResponse($response->isSuccessful())
            ->setQueueItemId($queueItemId);

        try {
            $this->apiLogRepository->save($apiLog);
        } catch (\Magento\Framework\Exception\CouldNotSaveException $exception) {
            $this->logger->critical(
                "WalkTheChat | Unable to save WalkTheChat API log into database. Error: {$exception->getMessage()}",
                $exception->getTrace()
            );
        }
    }
}
