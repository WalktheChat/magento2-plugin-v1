<?php
namespace Divante\Walkthechat\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Walkthechat Config Action Block
 *
 * @package  Divante\Walkthechat
 * @author   Divante Tech Team <tech@divante.pl>
 */
class Action extends Field
{
    protected $_template = 'Divante_Walkthechat::system/config/action.phtml';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Divante\Walkthechat\Helper\Data
     */
    protected $helper;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Divante\Walkthechat\Helper\Data $helper
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Divante\Walkthechat\Helper\Data $helper,
        Context $context,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;
        $this->helper = $helper;
    }

    /**
     * @param AbstractElement $element
     *
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * @return boolean
     */
    public function isConnected()
    {
        return $this->helper->isConnected();
    }

    /**
     * @return boolean
     */
    public function canConnect()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/general/app_id') && $this->scopeConfig->getValue('walkthechat_settings/general/app_key');
    }

    /**
     * @return string
     */
    public function getConnectUrl()
    {
        return $this->_urlBuilder->getUrl('walkthechat/auth/redirect');
    }

    /**
     * @return string
     */
    public function getDisconnectUrl()
    {
        return $this->_urlBuilder->getUrl('walkthechat/auth/disconnect');
    }
}
