<?php
namespace Divante\Walkthechat\Service;

/**
 * Walkthechat Abstract Service
 *
 * @package  Divante\Walkthechat\Service
 * @author   Divante Tech Team <tech@divante.pl>
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
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Client constructor
     *
     * @param \Divante\Walkthechat\Service\Client $serviceClient
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct(
        Client $serviceClient,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->serviceClient = $serviceClient;
        $this->jsonHelper = $jsonHelper;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function getConfig(string $path)
    {
        return $this->scopeConfig->getValue('walkthechat_settings/general/' . $path);
    }

    /**
     * @param $resource
     * @param array $params
     * @return mixed
     */
    public function request($resource, $params = [])
    {
        $headers = $resource->getHeaders();

        if (isset($headers['x-access-token'])) {
            $headers['x-access-token'] = $this->getConfig('token');
        }

        $path = $resource->getPath();

        if (isset($params['id'])) {
            $path = str_replace(':id', $params['id'], $path);
        }

        $response = $this->serviceClient->request($resource->getType(), $path, $params, $headers);

        return $this->jsonHelper->jsonDecode($response->getBody());
    }
}