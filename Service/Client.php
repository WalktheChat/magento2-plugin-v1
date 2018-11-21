<?php
namespace Divante\Walkthechat\Service;

/**
 * Walkthechat Service Client
 *
 * @package  Divante\Walkthechat\Service
 * @author   Divante Tech Team <tech@divante.pl>
 */
class Client
{
    const ENDPOINT = "https://cms-api-staging-v3.walkthechat.com/api/v1/";

    /**
     * @var \Divante\Walkthechat\HTTP\ZendClientFactory
     */
    private $httpClientFactory;

    /**
     * Client constructor
     *
     * @param HttpClientInterfaceFactory $httpClientFactory
     */
    public function __construct(\Divante\Walkthechat\HTTP\ZendClientFactory $httpClientFactory)
    {
        $this->httpClientFactory = $httpClientFactory;
    }

    public function getEndpoint()
    {
        return $this::ENDPOINT;
    }

    /**
     * @param string $type
     * @param string $path
     * @param array $data
     * @param array $headers
     * @return \Zend_Http_Response
     * @throws \Zend_Http_Client_Exception
     */
    public function request($type, $path, $data, $headers)
    {
        $httpClient = $this->httpClientFactory->create();
        $httpClient->setUri($this->getEndpoint() . $path);
        $httpClient->setHeaders($headers);

        if ($type == 'POST' || $type == 'PUT') {
            $httpClient->setParameterPost($data);
        } elseif ($type == 'GET') {
            $httpClient->setParameterGet($data);
        }

        return $httpClient->request($type);
    }
}
