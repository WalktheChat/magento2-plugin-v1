<?php
/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\Walkthechat\Controller\Adminhtml\Auth;

/**
 * Class Confirm
 *
 * @package Divante\Walkthechat\Controller\Adminhtml\Auth
 */
class Confirm extends \Magento\Backend\App\Action
{
    /**
     * @var \Divante\Walkthechat\Service\AuthorizeRepository
     */
    protected $authorizeRepository;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * {@inheritdoc}
     *
     * @param \Magento\Framework\App\RequestInterface          $request
     * @param \Divante\Walkthechat\Service\AuthorizeRepository $authorizeRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\RequestInterface $request,
        \Divante\Walkthechat\Service\AuthorizeRepository $authorizeRepository
    ) {
        $this->request             = $request;
        $this->authorizeRepository = $authorizeRepository;

        parent::__construct($context);
    }

    /**
     * Get token from Walkthechat
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $code = $this->request->getParam('code');

        try {
            $this->authorizeRepository->authorize($code);

            $this->messageManager->addSuccessMessage(__('App connected.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $this->_redirect('adminhtml/system_config/edit/section/walkthechat_settings');
    }
}
