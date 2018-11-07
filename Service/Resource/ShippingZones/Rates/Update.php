<?php
namespace Divante\Walkthechat\Service\Resource\ShippingZones\Rates;

/**
 * Walkthechat Service Shipping Zones Rates Update Resource
 *
 * @package  Divante\Walkthechat\Service
 * @author   Divante Tech Team <tech@divante.pl>
 */
class Update extends \Divante\Walkthechat\Service\Resource\AbstractResource
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
        $this->_type = 'put';
        $this->_path = 'shipping-zones/:id/rates/:fk';
    }
}
