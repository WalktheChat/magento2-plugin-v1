<?php
namespace Divante\Walkthechat\Controller\Adminhtml\Auth;

class Disconnect extends \Magento\Backend\App\Action
{
    protected $configWriter;
    protected $cacheTypeList;

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

    public function execute()
    {
        $this->configWriter->delete('walkthechat_settings/general/token');

        $this->cacheTypeList->cleanType('config');

        $this->messageManager->addSuccessMessage(__('App was disconnected.'));
        $this->_redirect('adminhtml/system_config/edit/section/walkthechat_settings');
    }
}