<?php
namespace Divante\Walkthechat\Service\Resource\Orders;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */
class Update extends \Divante\Walkthechat\Service\Resource\AbstractResource
{
    /**
     * @var string
     */
    protected $type = 'PUT';

    /**
     * @var string
     */
    protected $path = 'orders/:id';

    /**
     * @var array
     */
    protected $headers = [
        'x-access-token' => '',
        'Accept' => "application/json, appl-header 'Content-Type: application/json",
        'Content-Type' => "application/json"
    ];
}
