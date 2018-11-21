<?php
namespace Divante\Walkthechat\Service;

/**
 * Walkthechat Authorize Service
 *
 * @package  Divante\Walkthechat\Service
 * @author   Divante Tech Team <tech@divante.pl>
 */
class Authorize extends AbstractService
{
    /**
     * @var \Divante\Walkthechat\Service\Resource\Authorize
     */
    protected $authorizeResource;

    /**
     * Authorize constructor.
     * @param Client $serviceClient
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param Resource\Authorize $authorizeResource
     */
    public function __construct(
        Client $serviceClient,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        Resource\Authorize $authorizeResource
    )
    {
        parent::__construct($serviceClient, $jsonHelper, $scopeConfig);
        $this->authorizeResource = $authorizeResource;
    }

    /**
     * @param string $code
     *
     * @return string
     */
    public function authorize(string $code)
    {
        $data = [
            'code' => $code,
            'appId' => $this->getConfig('app_id'),
            'appSecret' => $this->getConfig('app_key'),
        ];

        $response = $this->request($this->authorizeResource, $data);

        return $response['token'];
    }
}
