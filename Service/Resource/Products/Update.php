<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Alex Yeremenko <madonzy13@gmail.com>
 * @copyright 2019 WalktheChat
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Service\Resource\Products;

/**
 * Class Update
 *
 * @package Divante\Walkthechat\Service\Resource\Products
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
        'Accept'       => 'application/json, application/xml, text/xml, application/javascript, text/javascript',
        'Content-Type' => 'application/json',
    ];
}
