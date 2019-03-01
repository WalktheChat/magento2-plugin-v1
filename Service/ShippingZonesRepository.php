<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Service;

/**
 * Class ShippingZonesRepository
 *
 * @package Divante\Walkthechat\Service
 */
class ShippingZonesRepository extends AbstractService
{
    /**
     * @var \Divante\Walkthechat\Service\Resource\ShippingZones\Create
     */
    protected $shippingZonesCreateResource;

    /**
     * @var \Divante\Walkthechat\Service\Resource\ShippingZones\Find
     */
    protected $shippingZonesFindResource;

    /**
     * @var \Divante\Walkthechat\Service\Resource\ShippingZones\Delete
     */
    protected $shippingZonesDeleteResource;

    /**
     * {@inheritdoc}
     *
     * @param \Divante\Walkthechat\Service\Resource\ShippingZones\Create $shippingZonesCreateResource
     * @param \Divante\Walkthechat\Service\Resource\ShippingZones\Find   $shippingZonesFindResource
     * @param \Divante\Walkthechat\Service\Resource\ShippingZones\Delete $shippingZonesDeleteResource
     */
    public function __construct(
        \Divante\Walkthechat\Service\Client $serviceClient,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Divante\Walkthechat\Helper\Data $helper,
        \Divante\Walkthechat\Log\ApiLogger $logger,
        \Divante\Walkthechat\Service\Resource\ShippingZones\Create $shippingZonesCreateResource,
        \Divante\Walkthechat\Service\Resource\ShippingZones\Find $shippingZonesFindResource,
        \Divante\Walkthechat\Service\Resource\ShippingZones\Delete $shippingZonesDeleteResource
    ) {
        $this->shippingZonesCreateResource = $shippingZonesCreateResource;
        $this->shippingZonesFindResource   = $shippingZonesFindResource;
        $this->shippingZonesDeleteResource = $shippingZonesDeleteResource;

        parent::__construct(
            $serviceClient,
            $jsonHelper,
            $helper,
            $logger
        );
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
