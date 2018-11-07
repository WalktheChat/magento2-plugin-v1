<?php
namespace Divante\Walkthechat\Service\Resource\ShippingZones\Rates;

/**
 * Walkthechat Service Shipping Zones Rates Create Resource
 *
 * @package  Divante\Walkthechat\Service
 * @author   Divante Tech Team <tech@divante.pl>
 */
class Create extends \Divante\Walkthechat\Service\Resource\AbstractResource
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
        $this->_type = 'post';
        $this->_path = 'shipping-zones/:id/rates';
    }
}
