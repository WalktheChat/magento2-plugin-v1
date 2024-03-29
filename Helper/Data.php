<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Alex Yeremenko <madonzy13@gmail.com>
 * @copyright 2019 WalktheChat
 *
 * @license   See LICENSE.txt for license details.
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
     * @var string
     */
    protected $baseCurrencyCode;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context              $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Backend\Model\UrlInterface                $urlBackendBuilder
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Backend\Model\UrlInterface $urlBackendBuilder
    ) {
        $this->scopeConfig       = $scopeConfig;
        $this->urlBackendBuilder = $urlBackendBuilder;

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
     * Validates Project ID with instance configuration Project ID
     *
     * @param string $projectId
     *
     * @throws \Divante\Walkthechat\Exception\InvalidMagentoInstanceException
     */
    public function validateProjectId($projectId)
    {
        if ($projectId !== $this->getProjectId()) {
            throw new \Divante\Walkthechat\Exception\InvalidMagentoInstanceException(
                __('Invalid instance request. Project ID is not supported for current Magento instance.')
            );
        }
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
     * Checks if base store currency is differs to order currency
     *
     * @param string $orderCurrency
     *
     * @return bool
     */
    public function isDifferentCurrency($orderCurrency)
    {
        if (null === $this->baseCurrencyCode) {
            $this->baseCurrencyCode = $this->scopeConfig->getValue(
                \Magento\Directory\Model\Currency::XML_PATH_CURRENCY_BASE,
                'default'
            );
        }

        return strtolower($orderCurrency) !== strtolower($this->baseCurrencyCode);
    }
}
