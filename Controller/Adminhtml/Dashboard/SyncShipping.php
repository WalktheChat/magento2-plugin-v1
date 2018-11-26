<?php

namespace Divante\Walkthechat\Controller\Adminhtml\Dashboard;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */
class SyncShipping extends \Magento\Backend\App\Action
{
    /**
     * @var \Divante\Walkthechat\Helper\Data
     */
    protected $helper;

    /**
     * @var \Divante\Walkthechat\Model\ShippingService
     */
    protected $shippingService;

    /**
     * SyncShipping constructor.
     *
     * @param \Magento\Backend\App\Action\Context        $context
     * @param \Divante\Walkthechat\Helper\Data           $helper
     * @param \Divante\Walkthechat\Model\ShippingService $shippingService
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Divante\Walkthechat\Helper\Data $helper,
        \Divante\Walkthechat\Model\ShippingService $shippingService
    ) {
        parent::__construct($context);
        $this->helper          = $helper;
        $this->shippingService = $shippingService;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        if ($this->helper->isTableRateActive()) {
            $this->shippingService->sync();
            $this->messageManager->addSuccessMessage(__('Shipping Synced.'));
        } else {
            $this->messageManager->addErrorMessage(__('Table Rate is disabled.'));
        }

        $this->_redirect('*/*/index');
    }
}
