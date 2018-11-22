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
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $urlBackendBuilder;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Backend\Model\UrlInterface $urlBackendBuilder $urlBackendBuilder
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Backend\Model\UrlInterface $urlBackendBuilder
    )
    {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
        $this->urlBackendBuilder = $urlBackendBuilder;
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
     * @return string
     */
    public function getAuthUrl()
    {
        $redirectUrl = $this->urlBackendBuilder->getUrl('walkthechat/auth/confirm');
        $appKey = $this->scopeConfig->getValue('walkthechat_settings/general/app_id');

        return $this->scopeConfig->getValue('walkthechat_settings/general/auth_url') . '?redirectUri=' . $redirectUrl . '&appId=' . $appKey;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/general/api_url');
    }

    /**
     * @param float $price
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
     * @param Magento/Catalog/Model/Product $product
     * @return array
     */
    public function prepareProductData($product)
    {
        $data = [
            'title' => [
                'en' => $product->getName(),
                'ch' => $product->getName()
            ],
            'bodyHtml' => [
                'en' => $product->getDescription(),
                'ch' => $product->getDescription()
            ],
            'variants' => [
                [
                    'title' => [
                        'en' => $product->getName(),
                        'ch' => $product->getName()
                    ],
                    'sku' => $product->getSku(),
                    'price' => $this->convertPrice($product->getPrice())
                ]
            ]
        ];

        return $data;
    }
}
