<?php
/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\Walkthechat\HTTP;

/**
 * Class ZendClient
 *
 * @package Divante\Walkthechat\HTTP
 */
class ZendClient extends \Magento\Framework\HTTP\ZendClient
{
    /**
     * Try to load curl extension
     *
     * @return $this|\Magento\Framework\HTTP\ZendClient
     * @throws \Zend_Http_Client_Exception
     */
    protected function _trySetCurlAdapter()
    {
        if (extension_loaded('curl')) {
            $this->setAdapter(new \Divante\Walkthechat\HTTP\Adapter\Curl());
        }

        return $this;
    }
}
