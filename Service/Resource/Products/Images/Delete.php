<?php
namespace Divante\Walkthechat\Service\Resource\Products\Images;

/**
 * Walkthechat Service Products Images Delete Resource
 *
 * @package  Divante\Walkthechat\Service
 * @author   Divante Tech Team <tech@divante.pl>
 */
class Delete extends \Divante\Walkthechat\Service\Resource\AbstractResource
{
    /**
     * @var string
     */
    protected $type = 'DELETE';

    /**
     * @var string
     */
    protected $path = 'products/:id/images/:fk';
}
