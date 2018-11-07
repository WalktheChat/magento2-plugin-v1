<?php
namespace Divante\Walkthechat\Service\Resource\ShippingZones\Countries;

/**
 * Walkthechat Service Shipping Zones Countires Resource
 *
 * @package  Divante\Walkthechat\Service
 * @author   Divante Tech Team <tech@divante.pl>
 */
class Get extends \Divante\Walkthechat\Service\Resource\AbstractResource
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
        $this->_type = 'get';
        $this->_path = 'shipping-zones/countries';
    }
}
