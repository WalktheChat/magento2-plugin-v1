<?php
/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\Walkthechat\Model;

/**
 * Class OrderService
 *
 * @package Divante\Walkthechat\Model
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
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Divante\Walkthechat\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Sales\Api\OrderItemRepositoryInterface
     */
    protected $orderItemRepository;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var \Magento\Quote\Api\Data\CartItemInterface[]
     */
    protected $preparedQuoteItems;

    /**
     * OrderService constructor.
     *
     * @param \Magento\Store\Model\StoreManagerInterface      $storeManager
     * @param \Magento\Quote\Model\QuoteFactory               $quoteFactory
     * @param \Magento\Quote\Model\QuoteManagement            $quoteManagement
     * @param \Magento\Sales\Model\OrderRepository            $orderRepository
     * @param \Magento\Catalog\Model\ProductRepository        $productRepository
     * @param \Magento\Framework\Registry                     $registry
     * @param \Divante\Walkthechat\Helper\Data                $helper
     * @param \Magento\Sales\Api\OrderItemRepositoryInterface $orderItemRepository
     * @param \Magento\Quote\Api\CartRepositoryInterface      $cartRepository
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Magento\Quote\Model\QuoteManagement $quoteManagement,
        \Magento\Sales\Model\OrderRepository $orderRepository,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\Registry $registry,
        \Divante\Walkthechat\Helper\Data $helper,
        \Magento\Sales\Api\OrderItemRepositoryInterface $orderItemRepository,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepository
    ) {
        $this->storeManager        = $storeManager;
        $this->quoteFactory        = $quoteFactory;
        $this->quoteManagement     = $quoteManagement;
        $this->orderRepository     = $orderRepository;
        $this->productRepository   = $productRepository;
        $this->registry            = $registry;
        $this->helper              = $helper;
        $this->orderItemRepository = $orderItemRepository;
        $this->cartRepository      = $cartRepository;
    }

    /**
     * Create/update order
     *
     * @param $data
     *
     * @return \Magento\Sales\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function processImport($data)
    {
        $quote = $this->initQuote($data);

        $this->addProductsIntoQuote($quote, $data);
        $this->proceedQuote($quote, $data);

        $order = $this->quoteManagement->submit($quote);

        if ($order instanceof \Magento\Sales\Api\Data\OrderInterface) {
            $this->setOrderTotals($order, $quote);

            $order
                ->setWalkthechatId($data['id'])
                ->setEmailSent(0);

            $this->orderRepository->save($order);
        }

        return $order;
    }

    /**
     * Initialize quote
     *
     * @param array $data
     *
     * @return \Magento\Quote\Model\Quote
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function initQuote(array $data)
    {
        $store = $this->storeManager->getStore();

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteFactory->create();

        $quote->setCheckoutMethod(\Magento\Quote\Api\CartManagementInterface::METHOD_GUEST);

        $quote->setStore($store);
        $quote->setCurrency();

        $quote->setCustomerIsGuest(true);
        $quote->setCustomerEmail($data['id'].'@walkthechat.com');
        $quote->setCustomerTaxvat($data['tax']['rate']);

        $addressData = $this->prepareAddressData($data);

        $quote->getBillingAddress()->addData($addressData);
        $quote->getShippingAddress()->addData($addressData);

        // set shipping price in the shipping career
        $this->registry->register(
            \Divante\Walkthechat\Model\Carrier\WTCShipping::WALKTHECHAT_SHIPPING_PRICE_KEY,
            (float)$data['shippingRate']['rate']
        );

        // make walkthechat payment and shipping available
        $this->registry->register('walkthechat_payment_and_shipping_available', true);

        return $quote;
    }

    /**
     * Add product into the cart
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @param array                                 $data
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function addProductsIntoQuote(\Magento\Quote\Api\Data\CartInterface $quote, array $data)
    {
        /** @var \Magento\Quote\Model\Quote $quote */

        foreach ($data['items']['products'] as $k => $item) {
            $product = $this->productRepository->get($item['variant']['sku']);

            $qty       = $item['quantity'];
            $quoteItem = $quote->addProduct($product, $qty);

            $discountAmount = $qty * $item['variant']['discount'];

            $quoteItem->setDiscountAmount($this->helper->convertPrice($discountAmount, false));
            $quoteItem->setBaseDiscountAmount($discountAmount);

            if ((float)$data['total']['grandTotal']['tax']) {
                $quoteItem->setTaxPercent($data['tax']['rate'] * 100);

                $taxAmount = $qty * ((float)$item['variant']['priceWithDiscount'] * (float)$data['tax']['rate']);

                $quoteItem->setTaxAmount($this->helper->convertPrice($taxAmount, false));
                $quoteItem->setBaseTaxAmount($taxAmount);
            }

            // set array data to save it into the order entity
            // so it can be used when order is canceled, refunded or shipped
            // and Magento -> WalkTheChat request can be filled properly
            $quoteItem->setData(
                'walkthechat_item_data',
                json_encode($data['itemsToFulfill'][$k], JSON_UNESCAPED_UNICODE)
            );

            $this->preparedQuoteItems[$product->getSku()] = clone $quoteItem;
        }
    }

    /**
     * Proceed payment, shipping, add totals
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @param array                                 $data
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function proceedQuote(\Magento\Quote\Api\Data\CartInterface $quote, array $data)
    {
        /** @var \Magento\Quote\Model\Quote $quote */

        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress
            ->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod('walkthechat_walkthechat');

        $quote->setPaymentMethod('walkthechat');

        $quote
            ->getPayment()
            ->setQuote($quote)
            ->importData(['method' => 'walkthechat']);

        $quote->collectTotals();

        $quote->setBaseCurrencyCode($data['total']['currency']);

        $quote->setShippingAmount($this->helper->convertPrice($data['total']['grandTotal']['shipping'], false));
        $quote->setBaseShippingAmount($data['total']['grandTotal']['shipping']);
        $quote->setShippingDescription('WalkTheChat - '.$data['shippingRate']['name']['en']);

        $quote->setSubtotal(
            $this->helper->convertPrice($data['total']['grandTotal']['totalWithoutDiscountAndTax'], false)
        );
        $quote->setBaseSubtotal($data['total']['grandTotal']['totalWithoutDiscountAndTax']);

        $quote->setSubtotalWithDiscount(
            $this->helper->convertPrice($data['total']['grandTotal']['totalWithoutTax'], false)
        );
        $quote->setBaseSubtotalWithDiscount($data['total']['grandTotal']['totalWithoutTax']);

        $quote->setTaxAmount($this->helper->convertPrice($data['total']['grandTotal']['tax'], false));
        $quote->setBaseTaxAmount($data['total']['grandTotal']['tax']);

        $quote->setDiscountAmount($this->helper->convertPrice($data['total']['grandTotal']['discount'], false));
        $quote->setBaseDiscountAmount($data['total']['grandTotal']['discount']);

        if (isset($data['coupon']['amount'])) {
            $quote->setDiscountDescription('WTC Coupon: '.$data['coupon']['code']);
        }

        $quote->setGrandTotal($this->helper->convertPrice($data['total']['grandTotal']['total'], false));
        $quote->setBaseGrandTotal($data['total']['grandTotal']['total']);

        $this->cartRepository->save($quote);
    }

    /**
     * Copy totals from quote
     *
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @param \Magento\Quote\Api\Data\CartInterface  $quote
     */
    protected function setOrderTotals(
        \Magento\Sales\Api\Data\OrderInterface $order,
        \Magento\Quote\Api\Data\CartInterface $quote
    ) {
        $order->setShippingAmount($quote->getShippingAmount());
        $order->setBaseShippingAmount($quote->getBaseShippingAmount());
        $order->setShippingDescription($quote->getShippingDescription());

        $order->setSubtotal($quote->getSubtotal());
        $order->setBaseSubtotal($quote->getBaseSubtotal());
        $order->setSubtotalWithDiscount($quote->getSubtotalWithDiscount());
        $order->setBaseSubtotalWithDiscount($quote->getBaseSubtotalWithDiscount());

        $order->setTaxAmount($quote->getTaxAmount());
        $order->setBaseTaxAmount($quote->getBaseTaxAmount());

        $order->setGrandTotal($quote->getGrandTotal());
        $order->setBaseGrandTotal($quote->getBaseGrandTotal());

        $order->setDiscountAmount($quote->getDiscountAmount());
        $order->setBaseDiscountAmount($quote->getBaseDiscountAmount());

        $order->setDiscountDescription($quote->getDiscountDescription());

        $order->setBaseTotalPaid($quote->getBaseGrandTotal());
        $order->setTotalPaid($quote->getGrandTotal());

        foreach ($order->getItems() as $item) {
            $quoteItem = $this->preparedQuoteItems[$item->getSku()];

            $item->setTaxPercent($quoteItem->getTaxPercent());

            $item->setDiscountAmount($quoteItem->getDiscountAmount());
            $item->setBaseDiscountAmount($quoteItem->getBaseDiscountAmount());

            $item->setTaxAmount($quoteItem->getTaxAmount());
            $item->setBaseTaxAmount($quoteItem->getBaseTaxAmount());

            $item->setData('walkthechat_item_data', $quoteItem->getData('walkthechat_item_data'));

            $this->orderItemRepository->save($item);
        }
    }

    /**
     * Prepare mapping for API address into magento one
     *
     * @param array $data
     *
     * @return array
     */
    protected function prepareAddressData(array $data)
    {
        $address = $data['deliveryAddress'];

        return [
            'firstname'            => substr($address['name'], 1),
            'lastname'             => $address['name'][0],
            'street'               => $address['address'].', '.$address['district'],
            'city'                 => $address['city'],
            'country_id'           => $address['countryCode'],
            'region'               => $address['province'],
            'postcode'             => $address['zipcode'],
            'telephone'            => $address['phone'],
            'fax'                  => '',
            'save_in_address_book' => false,
        ];
    }
}
