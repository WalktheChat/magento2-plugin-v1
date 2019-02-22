<?php
/**
 * @package   Divante\Walkthechat
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Model;

/**
 * Class WTCPaymentMethod
 *
 * @package Divante\Walkthechat\Model
 */
class WTCPayment extends \Magento\Payment\Model\Method\AbstractMethod
{
    /**
     * Payment code
     *
     * @var string
     */
    protected $_code = 'walkthechat';

    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isOffline = true;

    /**
     * {@inheritdoc}
     *
     * Allow only for import request
     */
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        return (bool)$this->_registry->registry('walkthechat_payment_and_shipping_available');
    }
}
