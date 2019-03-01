<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Service\Resource\ShippingZones\Rates;

/**
 * Class Find
 *
 * @package Divante\Walkthechat\Service\Resource\ShippingZones\Rates
 */
class Find extends \Divante\Walkthechat\Service\Resource\AbstractResource
{
    /**
     * @var string
     */
    protected $type = 'GET';

    /**
     * @var string
     */
    protected $path = 'shipping-zones/:id/rates/:fk';
}
