<?php

namespace Divante\Walkthechat\Helper;

use Magento\TestFramework\Event\Magento;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
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
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context                     $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface        $scopeConfig
     * @param \Magento\Backend\Model\UrlInterface                       $urlBackendBuilder $urlBackendBuilder
     * @param \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Backend\Model\UrlInterface $urlBackendBuilder,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
    ) {
        $this->scopeConfig         = $scopeConfig;
        $this->urlBackendBuilder   = $urlBackendBuilder;
        $this->stockItemRepository = $stockItemRepository;

        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/general/token');
    }

    /**
     * @return string
     */
    public function getProjectId()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/general/project_id');
    }

    /**
     * @return string
     */
    public function getShopName()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/general/shop_name');
    }

    /**
     * @return string
     */
    public function getAppId()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/general/app_id');
    }

    /**
     * @return string
     */
    public function getAppKey()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/general/app_key');
    }

    /**
     * @return boolean
     */
    public function isConnected()
    {
        return $this->getToken() ? true : false;
    }

    /**
     * @return boolean
     */
    public function canConnect()
    {
        return $this->getAppId() && $this->getAppKey();
    }

    /**
     * @return boolean
     */
    public function isEnabledProductSync()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/sync/product_sync_active') ? true : false;
    }

    /**
     * @return boolean
     */
    public function isEnabledOrderSync()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/sync/order_sync_active') ? true : false;
    }

    /**
     * @return boolean
     */
    public function isCurrencyConversionActive()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/currency/conversion_active') ? true : false;
    }

    /**
     * @return boolean
     */
    public function isTableRateActive()
    {
        return $this->scopeConfig->getValue('carriers/tablerate/active') ? true : false;
    }

    /**
     * @return string
     */
    public function getTableRateConditionName()
    {
        return $this->scopeConfig->getValue('carriers/tablerate/condition_name');
    }

    /**
     * @return string
     */
    public function getCurrencyConversionRate()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/currency/exchange_rate');
    }

    /**
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
     *
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function prepareProductData($product, $isNew = true)
    {
        $mainPrice        = $this->convertPrice($product->getPrice());
        $mainSpecialPrice = $this->convertPrice($product->getSpecialPrice());

        $data = [
            'manageInventory'       => true,
            'visibility'            => $product->getVisibility() != \Magento\Catalog\Model\Product\Visibility::VISIBILITY_NOT_VISIBLE,
            'displayPrice'          => $mainPrice,
            'displayCompareAtPrice' => $mainSpecialPrice,
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

            foreach ($configurableOptions as $option) {
                foreach ($option as $variation) {
                    $data['variantOptions'][] = $variation['attribute_code'];

                    break;
                }
            }

            /** @var \Magento\Catalog\Model\Product[] $children */
            $children = $product->getTypeInstance()->getUsedProducts($product);

            if ($children) {
                $data['variants'] = [];

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

                    if ($isNew) {
                        $data['variants'][$k]['title'] = [
                            'en' => $child->getName(),
                        ];
                    }

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
     * @param \Magento\Sales\Model\Order $order
     *
     * @return array
     */
    public function prepareOrderData($order)
    {
        $data = [
            'id' => $order->getWalkthechatId(),
        ];

        switch ($order->getState()) {
            case 'new':
                $data['status'] = 'waiting-for-payment';
                break;
            case 'processing':
                if ($order->hasInvoices() && $order->hasShipments()) {
                    $data['status'] = 'shipped';
                } elseif (!$order->hasInvoices() && $order->hasShipments()) {
                    $data['status'] = 'waiting-for-payment';
                } elseif ($order->hasInvoices() && !$order->hasShipments()) {
                    $data['status'] = 'waiting-for-shipment';
                }
                break;
            case 'complete':
                $tracksCollection = $order->getTracksCollection();

                $data['parcels'] = [];

                foreach ($tracksCollection->getItems() as $track) {
                    $data['parcels'][] = [
                        'trackingNumber' => $track->getTrackNumber(),
                        'carrier'        => $track->getTitle(),
                    ];
                }

                $data['status'] = 'shipped';
                break;
            case 'closed':
                $data['status'] = 'refunded';
                break;
            case 'canceled':
                $data['status'] = 'canceled';
                break;
        }

        return $data;
    }

    /**
     * Return Walk the chat ID form product
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     *
     * @return string|null
     */
    public function getWalkTheChatAttribute(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $walkTheChatIdAttribute = $product->getCustomAttribute('walkthechat_id');

        if ($walkTheChatIdAttribute instanceof \Magento\Framework\Api\AttributeValue) {
            return $walkTheChatIdAttribute->getValue();
        }

        return null;
    }
}
