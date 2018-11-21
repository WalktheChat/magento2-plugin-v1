<?php
namespace Divante\Walkthechat\Controller\Adminhtml\Auth;

class Confirm extends \Magento\Backend\App\Action
{
    protected $authorizeService;
    protected $request;
    protected $configWriter;
    protected $cacheTypeList;
    protected $cacheFrontendPool;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\RequestInterface $request,
        \Divante\Walkthechat\Service\Authorize $authorizeService,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
    )
    {
        parent::__construct($context);
        $this->request = $request;
        $this->authorizeService = $authorizeService;
        $this->configWriter = $configWriter;
        $this->cacheTypeList = $cacheTypeList;
    }

    public function execute()
    {
        $code = $this->request->getParam('code');

        $token = $this->authorizeService->authorize($code);
        $this->configWriter->save('walkthechat_settings/general/token', $token);

        $this->cacheTypeList->cleanType('config');

        $this->messageManager->addSuccessMessage(__('App connected.'));

        $this->_redirect('adminhtml/system_config/edit/section/walkthechat_settings');
    }
}