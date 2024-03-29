<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Alex Yeremenko <madonzy13@gmail.com>
 * @copyright 2019 WalktheChat
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
     * @throws \Zend_Http_Client_Exception
     */
    public function request(
        \Divante\Walkthechat\Service\Resource\AbstractResource $resource, 
        $params = [], 
        $isImageUpload = false
    ) {
        $headers = $resource->getHeaders();

        $headers['x-access-token'] = $this->helper->getToken();

        $path = $resource->getPath();

        // id to represent for logging
        $placeholderId = null;

        if (isset($params['id'])) {
            $path = str_replace(':id', $params['id'], $path);

            // set valid it to log proper path
            $placeholderId = $params['id'];

            unset($params['id']);
        }

        if (!$isImageUpload) {
            $params['projectId'] = $this->helper->getProjectId();
        }

        $response = $this->serviceClient->request($resource->getType(), $path, $params, $headers, $isImageUpload);

        // log into WalkTheChat log in Admin Panel
        $this->logger->log($resource, $params, $response, $placeholderId);

        if ($response->isError()) {
            throw new \Zend\Http\Client\Exception\RuntimeException('Invalid response.');
        }

        return $this->jsonHelper->jsonDecode($response->getBody());
    }
}
