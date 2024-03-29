<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Alex Yeremenko <madonzy13@gmail.com>
 * @copyright 2019 WalktheChat
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Service;

/**
 * Class AuthorizeRepository
 *
 * @package Divante\Walkthechat\Service
 */
class AuthorizeRepository extends AbstractService
{
    /**
     * @var \Divante\Walkthechat\Service\Resource\Authorize
     */
    protected $authorizeResource;

    /**
     * @var \Divante\Walkthechat\Service\Resource\Project
     */
    protected $projectResource;

    /**
     * @var \Magento\Framework\App\Config\Storage\WriterInterface
     */
    protected $configWriter;

    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $cacheTypeList;

    /**
     * {@inheritdoc}
     *
     * @param \Divante\Walkthechat\Service\Resource\Authorize $authorizeResource
     * @param \Divante\Walkthechat\Service\Resource\Project   $projectResource
     */
    public function __construct(
        \Divante\Walkthechat\Service\Client $serviceClient,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Divante\Walkthechat\Helper\Data $helper,
        \Divante\Walkthechat\Log\ApiLogger $logger,
        \Divante\Walkthechat\Service\Resource\Authorize $authorizeResource,
        \Divante\Walkthechat\Service\Resource\Project $projectResource,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
    ) {
        $this->authorizeResource = $authorizeResource;
        $this->projectResource   = $projectResource;
        $this->configWriter      = $configWriter;
        $this->cacheTypeList     = $cacheTypeList;

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
     * @return bool
     * @throws \Zend_Http_Client_Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function authorize(string $code)
    {
        $authorizeData = [
            'code'      => $code,
            'appId'     => $this->helper->getAppId(),
            'appSecret' => $this->helper->getAppKey(),
        ];

        $authorizeResponse = $this->request($this->authorizeResource, $authorizeData);
        $token             = isset($authorizeResponse['token']) ? $authorizeResponse['token'] : '';

        if (!$token) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Unable to fetch token.'));
        }

        $projectData = [
            'shopName'     => $this->helper->getShopName(),
            'access_token' => $token,
        ];

        $projectResponse = $this->request($this->projectResource, $projectData);
        $projectId       = isset($projectResponse['id']) ? $projectResponse['id'] : '';

        if (!$projectId) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Unable to fetch Project ID.'));
        }

        $this->configWriter->save('walkthechat_settings/general/token', $token);
        $this->configWriter->save('walkthechat_settings/general/project_id', $projectId);

        $this->cacheTypeList->cleanType('config');

        return true;
    }
}
