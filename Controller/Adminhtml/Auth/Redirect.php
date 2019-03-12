<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Alex Yeremenko <madonzy13@gmail.com>
 * @copyright 2019 WalktheChat
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Controller\Adminhtml\Auth;

/**
 * Class Redirect
 *
 * @package Divante\Walkthechat\Controller\Adminhtml\Auth
 */
class Redirect extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $resultFactory;

    /**
     * @var \Divante\Walkthechat\Helper\Data
     */
    protected $helper;

    /**
     * {@inheritdoc}
     *
     * @param \Magento\Framework\Controller\ResultFactory $resultFactory
     * @param \Divante\Walkthechat\Helper\Data            $helper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        \Divante\Walkthechat\Helper\Data $helper
    ) {
        $this->resultFactory = $resultFactory;
        $this->helper        = $helper;

        parent::__construct($context);
    }

    /**
     * Redirect to Walkthechat in order to connect with app
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultInstance */
        $resultInstance = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);

        $resultInstance->setUrl($this->helper->getAuthUrl());

        return $resultInstance;
    }
}
