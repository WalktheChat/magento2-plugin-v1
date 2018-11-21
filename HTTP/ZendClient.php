<?php
namespace Divante\Walkthechat\HTTP;

/**
 * Walkthechat Client
 *
 * @package  Divante\Walkthechat
 * @author   Divante Tech Team <tech@divante.pl>
 */
class ZendClient extends \Magento\Framework\HTTP\ZendClient
{
    /**
     * @return $this
     */
    protected function _trySetCurlAdapter()
    {
        if (extension_loaded('curl')) {
            $this->setAdapter(new \Divante\Walkthechat\HTTP\Adapter\Curl());
        }
        return $this;
    }
}
