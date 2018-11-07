<?php
namespace Divante\Walkthechat\Service\Resource\Products\Variants;

/**
 * Walkthechat Service Products Variants Create Resource
 *
 * @package  Divante\Walkthechat\Service
 * @author   Divante Tech Team <tech@divante.pl>
 */
class Create extends \Divante\Walkthechat\Service\Resource\AbstractResource
{
    /**
     * @var string
     */
    protected $_type;

    /**
     * @var string
     */
    protected $_path;

    /**
     * Resource constructor.
     */
    public function __construct()
    {
        $this->_type = 'post';
        $this->_path = 'products/:id/variants';
    }
}
