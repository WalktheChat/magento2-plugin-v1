<?php
namespace Divante\Walkthechat\Service;

/**
 * Walkthechat Products Service
 *
 * @package  Divante\Walkthechat\Service
 * @author   Divante Tech Team <tech@divante.pl>
 */
class Products extends AbstractService
{
    /**
     * @var Resource\Products\Create
     */
    protected $productCreateResource;

    /**
     * @var Resource\Products\Delete
     */
    protected $productDeleteResource;

    /**
     * @var Resource\Products\Find
     */
    protected $productFindResource;

    /**
     * Products constructor.
     * @param Client $serviceClient
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param Resource\Products\Create $productCreateResource
     * @param Resource\Products\Delete $productDeleteResource
     * @param Resource\Products\Find $productFindResource
     */
    public function __construct(
        Client $serviceClient,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        Resource\Products\Create $productCreateResource,
        Resource\Products\Delete $productDeleteResource,
        Resource\Products\Find $productFindResource
    )
    {
        parent::__construct($serviceClient, $jsonHelper, $scopeConfig);
        $this->productCreateResource = $productCreateResource;
        $this->productDeleteResource = $productDeleteResource;
        $this->productFindResource = $productFindResource;
    }

    public function create($data)
    {
        $response = $this->request($this->productCreateResource, $data);
        return $response['id'];
    }

    public function delete($id)
    {
        return $this->request($this->productDeleteResource, $id);
    }

    /**
     * @return mixed
     */
    public function find()
    {
        return $this->request($this->productFindResource);
    }
}
