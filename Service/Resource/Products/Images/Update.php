<?php
namespace Divante\Walkthechat\Service\Resource\Products\Images;

/**
 * Walkthechat Service Products Images Update Resource
 *
 * @package  Divante\Walkthechat\Service
 * @author   Divante Tech Team <tech@divante.pl>
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
    protected $path = 'products/:id/images/:fk';
}
