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
     * @var \Divante\Walkthechat\Service\Resource\Products\Create
     */
    protected $productCreateResource;

    /**
     * @var \Divante\Walkthechat\Service\Resource\Products\Delete
     */
    protected $productDeleteResource;

    /**
     * @var \Divante\Walkthechat\Service\Resource\Products\Find
     */
    protected $productFindResource;

    /**
     * @var \Divante\Walkthechat\Service\Resource\Products\Update
     */
    protected $productUpdateResource;

    /**
     * {@inheritdoc}
     *
     * @param \Divante\Walkthechat\Service\Resource\Products\Create $productCreateResource
     * @param \Divante\Walkthechat\Service\Resource\Products\Delete $productDeleteResource
     * @param \Divante\Walkthechat\Service\Resource\Products\Find   $productFindResource
     * @param \Divante\Walkthechat\Service\Resource\Products\Update $productUpdateResource
     */
    public function __construct(
        Client $serviceClient,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Divante\Walkthechat\Helper\Data $helper,
        \Divante\Walkthechat\Log\ApiLogger $logger,
        \Divante\Walkthechat\Service\Resource\Products\Create $productCreateResource,
        \Divante\Walkthechat\Service\Resource\Products\Delete $productDeleteResource,
        \Divante\Walkthechat\Service\Resource\Products\Find $productFindResource,
        \Divante\Walkthechat\Service\Resource\Products\Update $productUpdateResource
    ) {
        $this->productCreateResource = $productCreateResource;
        $this->productDeleteResource = $productDeleteResource;
        $this->productFindResource   = $productFindResource;
        $this->productUpdateResource = $productUpdateResource;

        parent::__construct(
            $serviceClient,
            $jsonHelper,
            $helper,
            $logger
        );
    }

    /**
     * Create product
     *
     * @param array $data
     *
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
     *
     * @param $id
     *
     * @return mixed
     * @throws \Zend_Http_Client_Exception
     */
    public function delete($id)
    {
        return $this->request($this->productDeleteResource, $id);
    }

    /**
     * Find product
     *
     * @return mixed
     * @throws \Zend_Http_Client_Exception
     */
    public function find()
    {
        return $this->request($this->productFindResource);
    }

    /**
     * Update product
     *
     * @param $data
     *
     * @return mixed
     * @throws \Zend_Http_Client_Exception
     */
    public function update($data)
    {
        return $this->request($this->productUpdateResource, $data);
    }
}
