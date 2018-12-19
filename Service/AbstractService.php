<?php

namespace Divante\Walkthechat\Service;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */
abstract class AbstractService
{
    /**
     * @var \Divante\Walkthechat\Service\Client
     */
    protected $serviceClient;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * @var \Divante\Walkthechat\Helper\Data
     */
    protected $helper;

    /**
     * @var \Divante\Walkthechat\Log\ApiLogger
     */
    protected $logger;

    /**
     * AbstractService constructor.
     *
     * @param \Divante\Walkthechat\Service\Client $serviceClient
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Divante\Walkthechat\Helper\Data    $helper
     * @param \Divante\Walkthechat\Log\ApiLogger  $logger
     */
    public function __construct(
        \Divante\Walkthechat\Service\Client $serviceClient,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Divante\Walkthechat\Helper\Data $helper,
        \Divante\Walkthechat\Log\ApiLogger $logger
    ) {
        $this->serviceClient = $serviceClient;
        $this->jsonHelper    = $jsonHelper;
        $this->helper        = $helper;
        $this->logger        = $logger;
    }

    /**
     * Send request to API
     *
     * @param \Divante\Walkthechat\Service\Resource\AbstractResource $resource
     * @param array                                                  $params
     *
     * @return mixed
     * @throws \Zend_Http_Client_Exception
     * @throws \Exception
     */
    public function request($resource, $params = [])
    {
        $headers = $resource->getHeaders();

        $headers['x-access-token'] = $this->helper->getToken();

        $path = $resource->getPath();

        if (isset($params['id'])) {
            $path = str_replace(':id', $params['id'], $path);
        }

        $params['projectId'] = $this->helper->getProjectId();

        $response = $this->serviceClient->request($resource->getType(), $path, $params, $headers);

        $this->logger->log($resource, $params, $response);

        if ($response->getStatus() == 200) {
            return $this->jsonHelper->jsonDecode($response->getBody());
        } else {
            throw new \Exception(
                __('API error. Check logs for more details.')
            );
        }
    }
}
