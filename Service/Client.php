<?php

namespace Divante\Walkthechat\Service;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */
class Client
{
    /**
     * @var \Divante\Walkthechat\HTTP\ZendClientFactory
     */
    protected $httpClientFactory;

    /**
     * @var \Divante\Walkthechat\Helper\Data
     */
    protected $helper;

    /**
     * Client constructor.
     *
     * @param \Divante\Walkthechat\HTTP\ZendClientFactory $httpClientFactory
     * @param \Divante\Walkthechat\Helper\Data            $helper
     */
    public function __construct(
        \Divante\Walkthechat\HTTP\ZendClientFactory $httpClientFactory,
        \Divante\Walkthechat\Helper\Data $helper
    ) {
        $this->httpClientFactory = $httpClientFactory;
        $this->helper            = $helper;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->helper->getApiUrl();
    }

    /**
     * Send request to API
     *
     * @param string $type
     * @param string $path
     * @param array  $data
     * @param array  $headers
     *
     * @return \Zend_Http_Response
     * @throws \Zend_Http_Client_Exception
     */
    public function request($type, $path, $data, $headers)
    {
        $httpClient = $this->httpClientFactory->create();
        $httpClient->setUri($this->getEndpoint().$path);
        $httpClient->setHeaders($headers);

        if ($type == 'POST' || $type == 'PUT') {
            $httpClient->setParameterPost($data);
        } elseif ($type == 'GET') {
            $httpClient->setParameterGet($data);
        }

        return $httpClient->request($type);
    }
}
