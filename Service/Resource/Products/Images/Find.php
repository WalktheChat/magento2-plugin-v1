<?php
namespace Divante\Walkthechat\Service\Resource\Products\Images;

/**
 * Walkthechat Service Products Images Find Resource
 *
 * @package  Divante\Walkthechat\Service
 * @author   Divante Tech Team <tech@divante.pl>
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
    protected $path = 'products/:id/images/:fk';
}
