<?php
/**
 * @package   Divante\Walkthechat
 *            
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Service\Resource;

/**
 * Class Authorize
 *
 * @package Divante\Walkthechat\Service\Resource
 */
class Authorize extends \Divante\Walkthechat\Service\Resource\AbstractResource
{
    /**
     * @var string
     */
    protected $type = 'POST';

    /**
     * @var string
     */
    protected $path = 'third-party-apps/authorize';
}
