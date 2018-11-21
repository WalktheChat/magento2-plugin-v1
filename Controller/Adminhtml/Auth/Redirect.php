<?php
namespace Divante\Walkthechat\Controller\Adminhtml\Auth;

class Redirect extends \Magento\Backend\App\Action
{
    protected $resultFactory;
    protected $urlBuilder;
    protected $scopeConfig;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        \Magento\Backend\Model\UrlInterface $urlBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        parent::__construct($context);
        $this->resultFactory = $resultFactory;
        $this->urlBuilder = $urlBuilder;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute()
    {
        $redirectUrl = $this->urlBuilder->getUrl('*/*/confirm');
        $appKey = $this->scopeConfig->getValue('walkthechat_settings/general/app_id');

        $resultInstance = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        $resultInstance->setUrl('https://cms-nuxt-api-v3.walkthechat.com/third-party-apps/auth?redirectUri=' . $redirectUrl . '&appId=' . $appKey);

        return $resultInstance;
    }
}
