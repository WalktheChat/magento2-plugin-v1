<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Service;

/**
 * Class ImagesRepository
 *
 * @package Divante\Walkthechat\Service
 */
class ImagesRepository extends AbstractService
{
    /**
     * @var \Divante\Walkthechat\Service\Resource\Images\Create
     */
    protected $imagesCreateResource;

    /**
     * {@inheritdoc}
     *
     * @param \Divante\Walkthechat\Service\Resource\Images\Create $imagesCreateResource
     */
    public function __construct(
        \Divante\Walkthechat\Service\Client $serviceClient,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Divante\Walkthechat\Helper\Data $helper,
        \Divante\Walkthechat\Log\ApiLogger $logger,
        \Divante\Walkthechat\Service\Resource\Images\Create $imagesCreateResource
    ) {
        $this->imagesCreateResource = $imagesCreateResource;

        parent::__construct(
            $serviceClient,
            $jsonHelper,
            $helper,
            $logger
        );
    }

    /**
     * Create image in CDN
     *
     * @param string $filePath
     *
     * @return string|null
     * @throws \Zend_Http_Client_Exception
     */
    public function create($filePath)
    {
        return $this->request($this->imagesCreateResource, ['file' => $filePath], true);
    }
}
