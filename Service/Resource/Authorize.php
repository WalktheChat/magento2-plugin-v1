<?php
namespace Divante\Walkthechat\Service\Resource;

/**
 * Walkthechat Service Authorize Resource
 *
 * @package  Divante\Walkthechat\Service
 * @author   Divante Tech Team <tech@divante.pl>
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
