<?php
/**
 * @package   Divante\Walkthechat
 *            
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Service;

/**
 * Class AbstractService
 *
 * @package Divante\Walkthechat\Service
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
     * @param bool                                                   $isImageUpload
     *
     * @return mixed
     * @throws \Magento\Framework\Exception\CronException
     * @throws \Zend_Http_Client_Exception
     */
    public function request($resource, $params = [], $isImageUpload = false)
    {
        $headers = $resource->getHeaders();

        $headers['x-access-token'] = $this->helper->getToken();

        $path = $resource->getPath();

        if (isset($params['id'])) {
            $path = str_replace(':id', $params['id'], $path);
        }

        if (!$isImageUpload) {
            $params['projectId'] = $this->helper->getProjectId();
        }

        $response = $this->serviceClient->request($resource->getType(), $path, $params, $headers, $isImageUpload);

        // log into WalkTheChat log in Admin Panel
        $this->logger->log($resource, $params, $response);

        if ($response->getStatus() == 200) {
            return $this->jsonHelper->jsonDecode($response->getBody());
        } else {
            throw new \Magento\Framework\Exception\CronException(
                __('API error. Check logs for more details.')
            );
        }
    }
}
