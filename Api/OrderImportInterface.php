<?php
/**
 * @package   Divante\Walkthechat
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Api;

/**
 * Interface OrderImportInterface
 *
 * @package Divante\Walkthechat\Api
 * @api
 */
interface OrderImportInterface
{
    /**
     * Import order from WalkTheChat CMS
     *
     * @param string $id
     * @param string $financialStatus
     * @param mixed  $itemsToFulfill
     * @param mixed  $items
     * @param mixed  $deliveryAddress
     * @param mixed  $shippingRate
     * @param mixed  $tax
     * @param mixed  $total
     * @param mixed  $coupon
     *
     * @return string
     */
    public function import($id, $financialStatus, $itemsToFulfill, $items, $deliveryAddress, $shippingRate, $tax, $total, $coupon = []);
}
