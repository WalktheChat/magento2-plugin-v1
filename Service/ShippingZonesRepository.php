<?php

namespace Divante\Walkthechat\Service;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */
class ShippingZonesRepository extends AbstractService
{
    /**
     * @var Resource\ShippingZones\Create
     */
    protected $shippingZonesCreateResource;

    /**
     * @var Resource\ShippingZones\Find
     */
    protected $shippingZonesFindResource;

    /**
     * @var Resource\ShippingZones\Delete
     */
    protected $shippingZonesDeleteResource;

    /**
     * ShippingZonesRepository constructor.
     *
     * @param Client                              $serviceClient
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Divante\Walkthechat\Helper\Data    $helper
     * @param Resource\ShippingZones\Create       $shippingZonesCreateResource
     * @param Resource\ShippingZones\Find         $shippingZonesFindResource
     * @param Resource\ShippingZones\Delete       $shippingZonesDeleteResource
     */
    public function __construct(
        Client $serviceClient,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Divante\Walkthechat\Helper\Data $helper,
        Resource\ShippingZones\Create $shippingZonesCreateResource,
        Resource\ShippingZones\Find $shippingZonesFindResource,
        Resource\ShippingZones\Delete $shippingZonesDeleteResource
    ) {
        parent::__construct($serviceClient, $jsonHelper, $helper);
        $this->shippingZonesCreateResource = $shippingZonesCreateResource;
        $this->shippingZonesFindResource   = $shippingZonesFindResource;
        $this->shippingZonesDeleteResource = $shippingZonesDeleteResource;
    }

    /**
     * create shipping zone
     *
     * @param $data
     *
     * @return mixed
     * @throws \Zend_Http_Client_Exception
     */
    public function create($data)
    {
        return $this->request($this->shippingZonesCreateResource, $data);
    }

    /**
     * delete shipping zone
     *
     * @param $id
     *
     * @return mixed
     * @throws \Zend_Http_Client_Exception
     */
    public function delete($id)
    {
        return $this->request($this->shippingZonesDeleteResource, ['id' => $id]);
    }

    /**
     * Find shipping zone
     *
     * @return mixed
     * @throws \Zend_Http_Client_Exception
     */
    public function find()
    {
        return $this->request($this->shippingZonesFindResource);
    }
}
