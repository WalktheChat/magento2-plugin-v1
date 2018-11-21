<?php
namespace Divante\Walkthechat\Service\Resource\Products;

/**
 * Walkthechat Service Products Delete Resource
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
    protected $path = 'products/:id';

    /**
     * @var boolean
     */
    protected $requireToken = true;
}
