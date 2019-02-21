<?php
/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\Walkthechat\Helper;

/**
 * Class Data
 *
 * @package Divante\Walkthechat\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Code of attribute used in orders and products
     */
    const ATTRIBUTE_CODE = 'walkthechat_id';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $urlBackendBuilder;

    /**
     * @var \Magento\CatalogInventory\Model\Stock\StockItemRepository
     */
    protected $stockItemRepository;

    /**
     * @var \Magento\Sales\Api\OrderItemRepositoryInterface
     */
    protected $orderItemRepository;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context                     $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface        $scopeConfig
     * @param \Magento\Backend\Model\UrlInterface                       $urlBackendBuilder $urlBackendBuilder
     * @param \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
     * @param \Magento\Sales\Api\OrderItemRepositoryInterface           $orderItemRepository
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Backend\Model\UrlInterface $urlBackendBuilder,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        \Magento\Sales\Api\OrderItemRepositoryInterface $orderItemRepository
    ) {
        $this->scopeConfig         = $scopeConfig;
        $this->urlBackendBuilder   = $urlBackendBuilder;
        $this->stockItemRepository = $stockItemRepository;
        $this->orderItemRepository = $orderItemRepository;

        parent::__construct($context);
    }

    /**
     * Return x-token-access from configuration
     *
     * @return string
     */
    public function getToken()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/general/token');
    }

    /**
     * Return project id from configuration
     *
     * @return string
     */
    public function getProjectId()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/general/project_id');
    }

    /**
     * Return shop name from configuration
     *
     * @return string
     */
    public function getShopName()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/general/shop_name');
    }

    /**
     * Return app ID from configuration
     *
     * @return string
     */
    public function getAppId()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/general/app_id');
    }

    /**
     * Return app key from configuration
     *
     * @return string
     */
    public function getAppKey()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/general/app_key');
    }

    /**
     * Check if integration connected
     *
     * @return boolean
     */
    public function isConnected()
    {
        return $this->getToken() ? true : false;
    }

    /**
     * Checks if all necessary data was set to try to connect
     *
     * @return boolean
     */
    public function canConnect()
    {
        return $this->getAppId() && $this->getAppKey();
    }

    /**
     * Checks if product synchronisation is enabled
     *
     * @return boolean
     */
    public function isEnabledProductSync()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/sync/product_sync_active') ? true : false;
    }

    /**
     * Checks if order synchronisation is enabled
     *
     * @return boolean
     */
    public function isEnabledOrderSync()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/sync/order_sync_active') ? true : false;
    }

    /**
     * Checks if currency conversation is enabled
     *
     * @return boolean
     */
    public function isCurrencyConversionActive()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/currency/conversion_active') ? true : false;
    }

    /**
     * Checks if table rate shipping is enabled
     *
     * @return boolean
     */
    public function isTableRateActive()
    {
        return $this->scopeConfig->getValue('carriers/tablerate/active') ? true : false;
    }

    /**
     * Return table rate condition name
     *
     * @return string
     */
    public function getTableRateConditionName()
    {
        return $this->scopeConfig->getValue('carriers/tablerate/condition_name');
    }

    /**
     * Return table rate condition rate
     *
     * @return string
     */
    public function getCurrencyConversionRate()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/currency/exchange_rate');
    }

    /**
     * Return table rate condition method
     *
     * @return string
     */
    public function getCurrencyConversionMethod()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/currency/round_method');
    }

    /**
     * Get URL for authorization
     *
     * @return string
     */
    public function getAuthUrl()
    {
        $redirectUrl = $this->urlBackendBuilder->getUrl('walkthechat/auth/confirm');
        $appKey      = $this->scopeConfig->getValue('walkthechat_settings/general/app_id');

        return $this->scopeConfig->getValue('walkthechat_settings/general/auth_url').'?redirectUri='.$redirectUrl.'&appId='.$appKey;
    }

    /**
     * Return API url
     *
     * @return string
     */
    public function getApiUrl()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/general/api_url');
    }

    /**
     * Convert price depending on method set in configuration
     *
     * @param float   $price
     * @param boolean $export
     *
     * @return float
     */
    public function convertPrice($price, $export = true)
    {
        if ($this->isCurrencyConversionActive()) {
            $rate = $this->getCurrencyConversionRate();

            if ($rate) {
                if ($export) {
                    if ($this->getCurrencyConversionMethod() == 2) {
                        if ($price * $rate < 1) {
                            $price = round($price * $rate, 1) * 10;
                            $digit = (int)substr($price, -1);
                            if ($digit < 8) {
                                $price += 8 - $digit;
                            } elseif ($digit == 9) {
                                $price += 9;
                            }
                            $price = $price / 10;
                        } else {
                            $price = ceil($price * $rate);
                            $digit = (int)substr($price, -1);
                            if ($digit < 8) {
                                $price += 8 - $digit;
                            } elseif ($digit == 9) {
                                $price += 9;
                            }
                        }
                    } else {
                        $price = round($price * $rate);
                    }
                } else {
                    $price = round($price / $rate, 2);
                }
            }
        }

        return $price;
    }

    /**
     * Prepare product data for API
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param bool                           $isNew
     * @param array                          $imagesData
     *
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function prepareProductData($product, $isNew = true, array $imagesData = [])
    {
        $mainPrice        = $this->convertPrice($product->getPrice());
        $mainSpecialPrice = $this->convertPrice($product->getSpecialPrice());

        $data = [
            'manageInventory'       => true,
            'visibility'            => $product->getVisibility() != \Magento\Catalog\Model\Product\Visibility::VISIBILITY_NOT_VISIBLE,
            'displayPrice'          => $mainPrice,
            'displayCompareAtPrice' => $mainSpecialPrice,
            'images'                => $imagesData['main'] ?? [],
            'variants'              => [
                [
                    'id'                => $product->getId(),
                    'inventoryQuantity' => $this->stockItemRepository->get($product->getId())->getQty(),
                    'weight'            => $product->getWeight(),
                    'requiresShipping'  => true,
                    'sku'               => $product->getSku(),
                    'price'             => $mainPrice,
                    'compareAtPrice'    => $mainSpecialPrice,
                    'visibility'        => $product->getVisibility() != \Magento\Catalog\Model\Product\Visibility::VISIBILITY_NOT_VISIBLE,
                    'taxable'           => (bool)$product->getTaxClassId(),
                ],
            ],
        ];

        // if is "update" action - don't update the title and description
        if ($isNew) {
            $data['title'] = [
                'en' => $product->getName(),
            ];

            $data['bodyHtml'] = [
                'en' => $product->getDescription(),
            ];

            $data['variants'][0]['title'] = [
                'en' => $product->getName(),
            ];
        }

        if ($product->getTypeId() === \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            $configurableOptions = $product->getTypeInstance()->getConfigurableOptions($product);

            $data['variantOptions'] = [];

            // send all available options in configurable product
            foreach ($configurableOptions as $option) {
                foreach ($option as $variation) {
                    $data['variantOptions'][] = $variation['attribute_code'];

                    break;
                }
            }

            /** @var \Magento\Catalog\Model\Product[] $children */
            $children = $product->getTypeInstance()->getUsedProducts($product);

            if ($children) {
                // if product is configurable - remove main product variant
                $data['variants'][0] = [];

                foreach ($children as $k => $child) {
                    $data['variants'][$k] = [
                        'id'                => $child->getId(),
                        'inventoryQuantity' => $this->stockItemRepository->get($child->getId())->getQty(),
                        'weight'            => $child->getWeight(),
                        'requiresShipping'  => true,
                        'sku'               => $child->getSku(),
                        'price'             => $this->convertPrice($child->getPrice()),
                        'compareAtPrice'    => $this->convertPrice($child->getSpecialPrice()),
                        'visibility'        => $child->getVisibility() != \Magento\Catalog\Model\Product\Visibility::VISIBILITY_NOT_VISIBLE,
                        'taxable'           => (bool)$child->getTaxClassId(),
                    ];

                    $imageData = $imagesData['children'][$child->getId()] ?? [];

                    // add images to each variant
                    if ($imageData && is_array($imageData)) {
                        $data['variants'][$k]['images'] = $imageData;
                    }

                    // if is "update" action - don't update the title
                    if ($isNew) {
                        $data['variants'][$k]['title'] = [
                            'en' => $child->getName(),
                        ];
                    }

                    // add available options for current variant
                    foreach ($data['variantOptions'] as $n => $attributeCode) {
                        $data['variants'][$k]['options'][] = [
                            'id'       => $attributeCode,
                            'name'     => [
                                'en' => $child->getResource()->getAttribute($attributeCode)->getFrontend()->getLabel($child),
                            ],
                            'position' => $n,
                            'value'    => [
                                'en' => $child->getAttributeText($attributeCode),
                            ],
                        ];
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Prepare order data for API
     *
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     *
     * @return array
     */
    public function prepareOrderData(\Magento\Sales\Api\Data\OrderInterface $order)
    {
        /** @var \Magento\Sales\Model\Order $order */

        $data = [
            'is_canceled' => $this->checkCancellation($order),
            'parcels'     => $this->checkShipments($order),
            'refunds'     => $this->checkRefund($order),
        ];

        return $data;
    }

    /**
     * Return Walk the chat ID form product
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     *
     * @return string|null
     */
    public function getWalkTheChatAttributeValue(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $value = null;

        $walkTheChatIdAttribute = $product->getCustomAttribute(
            \Divante\Walkthechat\Helper\Data::ATTRIBUTE_CODE
        );

        // try to fetch from loaded data first, if noting then make a separate request
        if ($walkTheChatIdAttribute instanceof \Magento\Framework\Api\AttributeValue) {
            $value = $walkTheChatIdAttribute->getValue();
        } else {
            /** @var \Magento\Catalog\Model\ResourceModel\Product $productResource */
            $productResource = $product->getResource();

            $value = $productResource->getAttributeRawValue(
                $product->getId(),
                \Divante\Walkthechat\Helper\Data::ATTRIBUTE_CODE,
                $product->getStore()->getId()
            );
        }

        return is_string($value) ? $value : null;
    }

    /**
     * Check shipments and return parcel data if exist
     *
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     *
     * @return array
     */
    protected function checkShipments(\Magento\Sales\Api\Data\OrderInterface $order)
    {
        /** @var \Magento\Sales\Model\Order $order */

        $shipmentCollection = $order->getShipmentsCollection();

        $data = [];

        foreach ($shipmentCollection->getItems() as $parcel) {
            // don't send already sent parcels
            if (!$parcel->getIsSentToWalkTheChat()) {
                // set default values in case tracks were not set
                $data[$parcel->getEntityId()]['data'] = [
                    'id'             => $order->getWalkthechatId(),
                    'trackingNumber' => null,
                    'carrier'        => null,
                ];

                foreach ($parcel->getTracks() as $track) {
                    $data[$parcel->getEntityId()]['data'] = [
                        'id'             => $order->getWalkthechatId(),
                        'trackingNumber' => $track->getTrackNumber(),
                        'carrier'        => $track->getTitle(),
                    ];

                    break; // take only first tracking number
                }

                // prepare parcel items before send to WalkTheChat
                foreach ($parcel->getItems() as $item) {
                    $orderItem                = $this->orderItemRepository->get($item->getOrderItemId());
                    $walkTheChatOrderItemData = json_decode($orderItem->getData('walkthechat_item_data'), true);

                    $walkTheChatOrderItemData['quantity'] = $item->getQty();

                    $data[$parcel->getEntityId()]['data']['items'][] = $walkTheChatOrderItemData;
                }

                $data[$parcel->getEntityId()]['entity'] = $parcel;
            }
        }

        return $data;
    }

    /**
     * Check if order was canceled
     *
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     *
     * @return bool
     */
    protected function checkCancellation(
        \Magento\Sales\Api\Data\OrderInterface $order
    ) {
        return $order->getState() === \Magento\Sales\Model\Order::STATE_CANCELED;
    }

    /**
     * Check if order was refunded
     *
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     *
     * @return array
     */
    protected function checkRefund(
        \Magento\Sales\Api\Data\OrderInterface $order
    ) {
        /** @var \Magento\Sales\Model\Order $order */

        $data       = [];
        $collection = $order->getCreditmemosCollection();

        foreach ($collection->getItems() as $creditMemo) {
            // don't send already sent parcels
            if (!$creditMemo->getIsSentToWalkTheChat()) {
                $comments = [];

                foreach ($creditMemo->getComments() as $comment) {
                    $comments[] = $comment->getComment();
                }

                $groupComment = implode("\n", $comments);

                $data[$creditMemo->getEntityId()]['data'] = [
                    'orderId' => $order->getWalkthechatId(),
                    'amount'  => $this->convertPrice($creditMemo->getGrandTotal()),
                    'comment' => $groupComment,
                ];

                $data[$creditMemo->getEntityId()]['entity'] = $creditMemo;
            }
        }

        return $data;
    }
}
