<?php

namespace Divante\Walkthechat\Service\Resource\Products;

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
    protected $type = 'PATCH';

    /**
     * @var string
     */
    protected $path = 'products/:id';

    /**
     * @var array
     */
    protected $headers = [
        'Accept'         => 'application/json, application/xml, text/xml, application/javascript, text/javascript',
        'Content-Type'   => 'application/json',
    ];
}
