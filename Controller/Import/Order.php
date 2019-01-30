<?php
/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\Walkthechat\Controller\Import;

/**
 * Class Index
 *
 * @package Divante\Walkthechat\Controller\Import
 */
class Order extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonResultFactory;

    /**
     * @var \Divante\Walkthechat\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Divante\Walkthechat\Model\OrderService
     */
    protected $orderService;

    /**
     * {@inheritdoc}
     *
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonResultFactory
     * @param \Divante\Walkthechat\Helper\Data                 $helper
     * @param \Magento\Framework\App\RequestInterface          $request
     * @param \Divante\Walkthechat\Model\OrderService          $orderService
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $jsonResultFactory,
        \Divante\Walkthechat\Helper\Data $helper,
        \Magento\Framework\App\RequestInterface $request,
        \Divante\Walkthechat\Model\OrderService $orderService
    ) {
        $this->jsonResultFactory = $jsonResultFactory;
        $this->helper            = $helper;
        $this->request           = $request;
        $this->orderService      = $orderService;

        parent::__construct($context);
    }

    /**
     * Import data from order's webhook
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $result = $this->jsonResultFactory->create();

        if ($this->helper->isEnabledOrderSync()) {
            $params = $this->request->getParams();

            try {
                $this->orderService->processImportRequest($params);
            } catch (\Exception $e) {
                $result->setHttpResponseCode(\Magento\Framework\Webapi\Exception::HTTP_BAD_REQUEST);
            }
        }

        return $result;
    }
}
