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
    protected $type = '';

    /**
     * @var string
     */
    protected $path = '';

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getHeaders() {
        return $this->headers;
    }
}
