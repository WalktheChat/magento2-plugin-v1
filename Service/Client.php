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
    const ENDPOINT = "https://cms-api-staging-v3.walkthechat.com/api/v1/third-party-apps/";

    /**
     * @var \Magento\Framework\HTTP\ZendClientFactory
     */
    private $_httpClientFactory;

    /**
     * Client constructor
     *
     * @param HttpClientInterfaceFactory $httpClientFactory
     */
    public function __construct(\Magento\Framework\HTTP\ZendClientFactory $httpClientFactory)
    {
        $this->_httpClientFactory = $httpClientFactory;
    }

    public function getEndpoint()
    {
        return $this::ENDPOINT;
    }

    /**
     * @param string $path
     * @param array $data
     * @param array $headers
     * @return string
     */
    public function post($path, $data, $headers = [])
    {
        $httpClient = $this->_httpClientFactory->create();
        $httpClient->setUri($this->getEndpoint() . $path);
        $httpClient->setHeaders($headers);
        $httpClient->setParameterPost($data);

        try {
            $response = $httpClient->request('POST');
        } catch (Exception $e) {
            /** TO-DO add error handler **/
        }

        return $response;
    }

    /**
     * @param string $path
     * @param array $data
     * @param array $headers
     * @return string
     */
    public function put($path, $data, $headers = [])
    {
        $httpClient = $this->_httpClientFactory->create();
        $httpClient->setUri($this->getEndpoint() . $path);
        $httpClient->setHeaders($headers);
        $httpClient->setParameterPost($data);

        try {
            $response = $httpClient->request('PUT');
        } catch (Exception $e) {
            /** TO-DO add error handler **/
        }

        return $response;
    }

    /**
     * @param string $path
     * @param array $data
     * @param array $headers
     * @return string
     */
    public function get($path, $data, $headers = [])
    {
        $httpClient = $this->_httpClientFactory->create();
        $httpClient->setUri($this->getEndpoint() . $path);
        $httpClient->setHeaders($headers);
        $httpClient->setParameterGet($data);

        try {
            $response = $httpClient->request('GET');
        } catch (Exception $e) {
            /** TO-DO add error handler **/
        }

        return $response;
    }

    /**
     * @param string $path
     * @param array $headers
     * @return string
     */
    public function delete($path, $headers = [])
    {
        $httpClient = $this->_httpClientFactory->create();
        $httpClient->setUri($this->getEndpoint() . $path);
        $httpClient->setHeaders($headers);

        try {
            $response = $httpClient->request('GET');
        } catch (Exception $e) {
            /** TO-DO add error handler **/
        }

        return $response;
    }
}
