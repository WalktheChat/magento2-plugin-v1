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
 * Class Project
 *
 * @package Divante\Walkthechat\Service\Resource
 */
class Project extends \Divante\Walkthechat\Service\Resource\AbstractResource
{
    /**
     * @var string
     */
    protected $type = 'GET';

    /**
     * @var string
     */
    protected $path = 'projects/search';
}
