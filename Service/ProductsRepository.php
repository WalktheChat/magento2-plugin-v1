<?php
namespace Divante\Walkthechat\Service;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */
class ProductsRepository extends AbstractService
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
     * @param \Divante\Walkthechat\Helper\Data $helper
     * @param Resource\Products\Create $productCreateResource
     * @param Resource\Products\Delete $productDeleteResource
     * @param Resource\Products\Find $productFindResource
     */
    public function __construct(
        Client $serviceClient,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Divante\Walkthechat\Helper\Data $helper,
        Resource\Products\Create $productCreateResource,
        Resource\Products\Delete $productDeleteResource,
        Resource\Products\Find $productFindResource
    )
    {
        parent::__construct($serviceClient, $jsonHelper, $helper);
        $this->productCreateResource = $productCreateResource;
        $this->productDeleteResource = $productDeleteResource;
        $this->productFindResource = $productFindResource;
    }

    /**
     * Create product
     * @param array $data
     * @return string|null
     * @throws \Zend_Http_Client_Exception
     */
    public function create($data)
    {
        $response = $this->request($this->productCreateResource, $data);

        return isset($response['id']) ? $response['id'] : null;
    }

    /**
     * Delete product
     * @param $id
     * @return mixed
     * @throws \Zend_Http_Client_Exception
     */
    public function delete($id)
    {
        return $this->request($this->productDeleteResource, $id);
    }

    /**
     * Find product
     * @return mixed
     * @throws \Zend_Http_Client_Exception
     */
    public function find()
    {
        return $this->request($this->productFindResource);
    }
}
