<?php
namespace Divante\Walkthechat\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Walkthechat Config Status Block
 *
 * @package  Divante\Walkthechat
 * @author   Divante Tech Team <tech@divante.pl>
 */
class Status extends Field
{
    protected $_template = 'Divante_Walkthechat::system/config/status.phtml';

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
    ) {
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
}
