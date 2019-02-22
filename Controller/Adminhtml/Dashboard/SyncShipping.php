<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Controller\Adminhtml\Dashboard;

/**
 * Class SyncShipping
 *
 * @package Divante\Walkthechat\Controller\Adminhtml\Dashboard
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
     * {@inheritdoc}
     *
     * @param \Divante\Walkthechat\Helper\Data           $helper
     * @param \Divante\Walkthechat\Model\ShippingService $shippingService
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Divante\Walkthechat\Helper\Data $helper,
        \Divante\Walkthechat\Model\ShippingService $shippingService
    ) {
        $this->helper          = $helper;
        $this->shippingService = $shippingService;

        parent::__construct($context);
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
