<?php
namespace Divante\Walkthechat\Controller\Adminhtml\Auth;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */
class Disconnect extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\App\Config\Storage\WriterInterface
     */
    protected $configWriter;

    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $cacheTypeList;

    /**
     * Disconnect constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
    )
    {
        parent::__construct($context);
        $this->configWriter = $configWriter;
        $this->cacheTypeList = $cacheTypeList;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $this->configWriter->delete('walkthechat_settings/general/token');
        $this->cacheTypeList->cleanType('config');
        $this->messageManager->addSuccessMessage(__('App was disconnected.'));
        $this->_redirect('adminhtml/system_config/edit/section/walkthechat_settings');
    }
}