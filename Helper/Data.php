<?php
namespace Divante\Walkthechat\Helper;
use Magento\TestFramework\Event\Magento;

/**
 * Walkthechat Helper
 *
 * @package  Divante\Walkthechat
 * @author   Divante Tech Team <tech@divante.pl>
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return boolean
     */
    public function isConnected()
    {
        return $this->scopeConfig->getValue('walkthechat_settings/general/token') ? true : false;
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
     * @param float $price
     * @param boolean $export
     *
     * @return float
     */
    public function convertPrice($price, $export = true)
    {
        if ($this->scopeConfig->getValue('walkthechat_settings/currency/conversion_active')) {
            $rate = $this->scopeConfig->getValue('walkthechat_settings/currency/exchange_rate');

            if ($rate) {
                if ($export) {
                    if ($this->scopeConfig->getValue('walkthechat_settings/currency/round_method') == 2) {
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
