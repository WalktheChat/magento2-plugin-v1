<?php

namespace Divante\Walkthechat\Service;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */
class AuthorizeRepository extends AbstractService
{
    /**
     * @var \Divante\Walkthechat\Service\Resource\Authorize
     */
    protected $authorizeResource;

    /**
     * {@inheritdoc}
     *
     * @param \Divante\Walkthechat\Service\Resource\Authorize $authorizeResource
     */
    public function __construct(
        Client $serviceClient,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Divante\Walkthechat\Helper\Data $helper,
        \Divante\Walkthechat\Log\ApiLogger $logger,
        \Divante\Walkthechat\Service\Resource\Authorize $authorizeResource
    ) {
        $this->authorizeResource = $authorizeResource;

        parent::__construct(
            $serviceClient,
            $jsonHelper,
            $helper,
            $logger
        );
    }

    /**
     * @param string $code
     *
     * @return mixed
     * @throws \Zend_Http_Client_Exception
     */
    public function authorize(string $code)
    {
        $data = [
            'code'      => $code,
            'appId'     => $this->helper->getAppId(),
            'appSecret' => $this->helper->getAppKey(),
        ];

        $response = $this->request($this->authorizeResource, $data);

        return $response['token'];
    }
}
