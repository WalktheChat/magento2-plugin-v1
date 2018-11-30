<?php
namespace Divante\Walkthechat\Model;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */
class OrderService
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Quote\Model\QuoteFactory
     */
    protected $quoteFactory;

    /**
     * @var \Magento\Quote\Model\QuoteManagement \
     */
    protected $quoteManagement;

    /**
     * @var \Magento\Sales\Model\OrderRepository
     */
    protected $orderRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * OrderService constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Quote\Model\QuoteFactory $quoteFactory
     * @param \Magento\Quote\Model\QuoteManagement $quoteManagement
     * @param \Magento\Sales\Model\OrderRepository $orderRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Catalog\Model\ProductRepository $productRepository
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Magento\Quote\Model\QuoteManagement $quoteManagement,
        \Magento\Sales\Model\OrderRepository $orderRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Catalog\Model\ProductRepository $productRepository
    )
    {
        $this->storeManager = $storeManager;
        $this->quoteFactory = $quoteFactory;
        $this->quoteManagement = $quoteManagement;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->productRepository = $productRepository;
    }

    /**
     * Create/update order
     *
     * @param $data
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function processImportRequest($data)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('walkthechat_id', $data['id'], 'eq')
            ->create();

        $orderList = $this->orderRepository->getList($searchCriteria)
            ->getItems();

        if (isset($orderList[0])) {
            $order = $orderList[0];

            // TO-DO update order status once we know data structure
        } else {
            $store = $this->storeManager->getStore();

            $quote = $this->quote->create();
            $quote->setStore($store);
            $quote->setCurrency();

            foreach ($data['items'] as $item) {
                $product = $this->productRepository->get($item['sku']);
                $quote->addProduct(
                    $product,
                    intval($item['qty'])
                );
            }

            $quote->getBillingAddress()->addData($data['billing_address']);
            $quote->getShippingAddress()->addData($data['shipping_address']);

            $shippingAddress = $quote->getShippingAddress();
            $shippingAddress->setCollectShippingRates(true)
                ->collectShippingRates()
                ->setShippingMethod('freeshipping_freeshipping');

            $quote->setPaymentMethod('checkmo');
            $quote->save();

            $quote->getPayment()->importData(['method' => 'checkmo']);
            $quote->collectTotals()
                ->save();

            $this->quoteManagement->submit($quote);
        }
    }
}