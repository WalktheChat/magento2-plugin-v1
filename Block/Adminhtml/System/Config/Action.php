<?php
namespace Divante\Walkthechat\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */
class Action extends Field
{
    protected $_template = 'Divante_Walkthechat::system/config/action.phtml';

    /**
     * @var \Divante\Walkthechat\Helper\Data
     */
    protected $helper;

    /**
     * Constructor
     *
     * @param \Divante\Walkthechat\Helper\Data $helper
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        \Divante\Walkthechat\Helper\Data $helper,
        Context $context,
        array $data = []
    )
    {
        parent::__construct($context, $data);
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
        return $this->helper->canConnect();
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
