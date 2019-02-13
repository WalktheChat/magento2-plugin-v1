<?php
/**
 * @package   Divante\Walkthechat
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\Walkthechat\Model\Import;

/**
 * Class RequestValidator
 *
 * @package Divante\Walkthechat\Model\Import
 */
class RequestValidator
{
    /**
     * Validates params and throw exception if validation failed
     *
     * @param string $id
     * @param array  $items
     * @param array  $deliveryAddress
     * @param array  $shippingRate
     * @param array  $tax
     * @param array  $coupon
     * @param array  $total
     *
     * @return array
     * @throws \Magento\Framework\Exception\ValidatorException
     */
    public function validate($id, $items, $deliveryAddress, $shippingRate, $tax, $total, $coupon)
    {
        $this->validateId($id);
        $this->validateItems($items);
        $this->validateDeliveryAddress($deliveryAddress);
        $this->validateShippingRate($shippingRate);
        $this->validateTax($tax);
        $this->validateTotal($total);

        return compact('id', 'items', 'deliveryAddress', 'shippingRate', 'tax', 'total', 'coupon');
    }

    /**
     * Throws exception if items structure is invalid
     *
     * @param array $items
     *
     * @return bool
     * @throws \Magento\Framework\Exception\ValidatorException
     */
    protected function validateItems($items)
    {
        if (
            is_array($items['products'])
            && isset($items['products'][0])
            && isset($items['products'][0]['variant']['sku'])
            && isset($items['products'][0]['quantity'])
            && isset($items['products'][0]['variant']['discount'])
            && isset($items['products'][0]['variant']['priceWithDiscount'])
        ) {
            return true;
        }

        throw new \Magento\Framework\Exception\ValidatorException(
            __('Unable to proceed order import. Items has invalid structure.')
        );
    }

    /**
     * Throws exception if delivery address structure is invalid
     *
     * @param array $deliveryAddress
     *
     * @return bool
     * @throws \Magento\Framework\Exception\ValidatorException
     */
    protected function validateDeliveryAddress($deliveryAddress)
    {
        if (
            isset($deliveryAddress['name'])
            && isset($deliveryAddress['address'])
            && isset($deliveryAddress['district'])
            && isset($deliveryAddress['city'])
            && isset($deliveryAddress['countryCode'])
            && isset($deliveryAddress['province'])
            && isset($deliveryAddress['zipcode'])
            && isset($deliveryAddress['phone'])
        ) {
            return true;
        }

        throw new \Magento\Framework\Exception\ValidatorException(
            __('Unable to proceed order import. Delivery address has invalid structure.')
        );
    }

    /**
     * Throws exception if shipping rate structure is invalid
     *
     * @param array $shippingRate
     *
     * @return bool
     * @throws \Magento\Framework\Exception\ValidatorException
     */
    protected function validateShippingRate($shippingRate)
    {
        if (
            isset($shippingRate['rate'])
            && isset($shippingRate['name']['en'])
        ) {
            return true;
        }

        throw new \Magento\Framework\Exception\ValidatorException(
            __('Unable to proceed order import. Shipping rate has invalid structure.')
        );
    }

    /**
     * Throws exception if tax structure is invalid
     *
     * @param array $tax
     *
     * @return bool
     * @throws \Magento\Framework\Exception\ValidatorException
     */
    protected function validateTax($tax)
    {
        if (isset($tax['rate'])) {
            return true;
        }

        throw new \Magento\Framework\Exception\ValidatorException(
            __('Unable to proceed order import. Tax has invalid structure.')
        );
    }

    /**
     * Throws exception if total structure is invalid
     *
     * @param array $total
     *
     * @return bool
     * @throws \Magento\Framework\Exception\ValidatorException
     */
    protected function validateTotal($total)
    {
        if (
            isset($total['grandTotal']['tax'])
            && isset($total['grandTotal']['shipping'])
            && isset($total['grandTotal']['totalWithoutDiscountAndTax'])
            && isset($total['grandTotal']['totalWithoutTax'])
            && isset($total['grandTotal']['tax'])
            && isset($total['grandTotal']['discount'])
            && isset($total['grandTotal']['total'])
        ) {
            return true;
        }

        throw new \Magento\Framework\Exception\ValidatorException(
            __('Unable to proceed order import. Total has invalid structure.')
        );
    }

    /**
     * Throws exception if ID is invalid
     *
     * @param string $id
     *
     * @return bool
     * @throws \Magento\Framework\Exception\ValidatorException
     */
    protected function validateId($id)
    {
        if (isset($id)) {
            return true;
        }

        throw new \Magento\Framework\Exception\ValidatorException(
            __('Unable to proceed order import. Id was not passed.')
        );
    }
}
