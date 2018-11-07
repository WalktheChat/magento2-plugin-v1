<?php
namespace Divante\Walkthechat\Service\Resource\ShippingZones;

/**
 * Walkthechat Service Shipping Zones Delete Resource
 *
 * @package  Divante\Walkthechat\Service
 * @author   Divante Tech Team <tech@divante.pl>
 */
class Delete extends \Divante\Walkthechat\Service\Resource\AbstractResource
{
    /**
     * @var string
     */
    protected $_type;

    /**
     * @var string
     */
    protected $_path;

    /**
     * Resource constructor.
     */
    public function __construct()
    {
        $this->_type = 'delete';
        $this->_path = 'shipping-zones/:id';
    }
}
