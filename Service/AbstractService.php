<?php
namespace Divante\Walkthechat\Service;

/**
 * Walkthechat Abstract Service
 *
 * @package  Divante\Walkthechat\Service
 * @author   Divante Tech Team <tech@divante.pl>
 */
class AbstractService
{
    /**
     * @var \Divante\Walkthechat\Service\Client
     */
    protected $_serviceClient;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var boolean
     */
    protected $_requiresToken = false;

    /**
     * Client constructor
     *
     * @param \Divante\Walkthechat\Service\Client $serviceClient
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {
        $this->_objectManager = $objectManager;
    }

    /**
     * Set Service Client
     *
     * @param \Divante\Walkthechat\Service\Client
     */
    public function setClient($serviceClient)
    {
        $this->_serviceClient = $serviceClient;
    }

    /**
     * Get Service Client
     *
     * @return \Divante\Walkthechat\Service\Client
     */
    public function getClient()
    {
        return $this->_serviceClient;
    }

    /**
     * Get Requires Token Flag
     *
     * @return boolean
     */
    public function requiresToken()
    {
        return $this->_requiresToken;
    }

    /**
     * Do the request to the Service Client
     *
     * @param \Divante\Walkthechat\Service\Resource\AbstractResource $resourceModel
     * @param array $params
     */
    public function request($resourceModel, $params)
    {
        switch($resourceModel->getType()) {
            case 'post' :
                return $this->_serviceClient->post($resourceModel->getPath(), $params);
            case 'put' :
                return $this->_serviceClient->put($resourceModel->getPath(), $params);
            case 'delete' :
                return $this->_serviceClient->delete($resourceModel->getPath());
            default :
                return $this->_serviceClient->get($resourceModel->getPath(), $params);
        }
    }
}