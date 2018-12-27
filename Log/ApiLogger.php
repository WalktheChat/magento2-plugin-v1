<?php
/**
 * @package   Divante\Walkthechat
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
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
     * ApiLogger constructor.
     *
     * @param \Divante\Walkthechat\Model\ApiLogFactory    $apiLogFactory
     * @param \Divante\Walkthechat\Model\ApiLogRepository $apiLogRepository
     * @param \Psr\Log\LoggerInterface                    $logger
     */
    public function __construct(
        \Divante\Walkthechat\Model\ApiLogFactory $apiLogFactory,
        \Divante\Walkthechat\Model\ApiLogRepository $apiLogRepository,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->apiLogFactory    = $apiLogFactory;
        $this->apiLogRepository = $apiLogRepository;
        $this->logger           = $logger;
    }

    /**
     * Log API into database
     *
     * @param \Divante\Walkthechat\Service\Resource\AbstractResource $requestResource
     * @param array                                                  $params
     * @param \Zend_Http_Response                                    $response
     */
    public function log(
        \Divante\Walkthechat\Service\Resource\AbstractResource $requestResource,
        array $params,
        \Zend_Http_Response $response
    ) {
        /** @var \Divante\Walkthechat\Api\Data\ApiLogInterface $apiLog */
        $apiLog = $this->apiLogFactory->create();

        $responseText = $response->asString();
        $path         = $requestResource->getPath();

        if (isset($params['id'])) {
            $path = str_replace(':id', $params['id'], $path);
        }

        $apiLog
            ->setRequestPath($path)
            ->setRequestParams($params)
            ->setRequestMethod($requestResource->getType())
            ->setResponseCode($response::extractCode($responseText))
            ->setResponseData(json_decode($response->getBody(), true))
            ->setIsSuccessResponse($response->isSuccessful());

        try {
            $this->apiLogRepository->save($apiLog);
        } catch (\Magento\Framework\Exception\CouldNotSaveException $exception) {
            $this->logger->critical(
                "Unable to save Walkthechat API log into database. Error: {$exception->getMessage()}",
                $exception->getTrace()
            );
        }
    }
}
