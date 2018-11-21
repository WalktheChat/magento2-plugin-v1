<?php
namespace Divante\Walkthechat\Service\Resource\Products\Images;

/**
 * Walkthechat Service Products Images Create Resource
 *
 * @package  Divante\Walkthechat\Service
 * @author   Divante Tech Team <tech@divante.pl>
 */
class Create extends \Divante\Walkthechat\Service\Resource\AbstractResource
{
    /**
     * @var string
     */
    protected $type = 'POST';

    /**
     * @var string
     */
    protected $path = 'products/:id/images';
}
