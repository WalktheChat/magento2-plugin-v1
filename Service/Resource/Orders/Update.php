<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Alex Yeremenko <madonzy13@gmail.com>
 * @copyright 2019 WalktheChat
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Service\Resource\Orders;

/**
 * Class Update
 *
 * @package Divante\Walkthechat\Service\Resource\Orders
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
        'Accept'       => "application/json, appl-header 'Content-Type: application/json",
        'Content-Type' => "application/json",
    ];
}
