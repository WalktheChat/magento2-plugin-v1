<?php
namespace Divante\Walkthechat\Service\Resource;

/**
 * Walkthechat Service Abstract Resource Class
 *
 * @package  Divante\Walkthechat\Service
 * @author   Divante Tech Team <tech@divante.pl>
 */
class AbstractResource
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
     * @return string
     */
    public function getType() {
        return $this->_type;
    }

    /**
     * @return string
     */
    public function getPath() {
        return $this->_path;
    }
}
