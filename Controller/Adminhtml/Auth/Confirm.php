<?php

namespace Divante\Walkthechat\Controller\Adminhtml\Auth;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
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
     * @var \Magento\Framework\App\Config\Storage\WriterInterface
     */
    protected $configWriter;

    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $cacheTypeList;

    /**
     * @var
     */
    protected $cacheFrontendPool;

    /**
     * Confirm constructor.
     *
     * @param \Magento\Backend\App\Action\Context                   $context
     * @param \Magento\Framework\App\RequestInterface               $request
     * @param \Divante\Walkthechat\Service\AuthorizeRepository      $authorizeRepository
     * @param \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
     * @param \Magento\Framework\App\Cache\TypeListInterface        $cacheTypeList
     * @param \Magento\Framework\App\Cache\Frontend\Pool            $cacheFrontendPool
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\RequestInterface $request,
        \Divante\Walkthechat\Service\AuthorizeRepository $authorizeRepository,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
    ) {
        parent::__construct($context);
        $this->request             = $request;
        $this->authorizeRepository = $authorizeRepository;
        $this->configWriter        = $configWriter;
        $this->cacheTypeList       = $cacheTypeList;
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
            $token = $this->authorizeRepository->authorize($code);
            $this->configWriter->save('walkthechat_settings/general/token', $token);
            $this->cacheTypeList->cleanType('config');
            $this->messageManager->addSuccessMessage(__('App connected.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $this->_redirect('adminhtml/system_config/edit/section/walkthechat_settings');
    }
}
