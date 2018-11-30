<?php

namespace Divante\Walkthechat\HTTP;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
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
