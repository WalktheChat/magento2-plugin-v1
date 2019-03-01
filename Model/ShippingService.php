<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Model;

/**
 * Class ShippingService
 *
 * @package Divante\Walkthechat\Model
 */
class ShippingService
{
    /**
     * @var \Divante\Walkthechat\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\OfflineShipping\Model\ResourceModel\Carrier\Tablerate\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Divante\Walkthechat\Service\ShippingZonesRepository
     */
    protected $shippingZonesRepository;

    /**
     * ShippingService constructor.
     *
     * @param \Divante\Walkthechat\Helper\Data                                                 $helper
     * @param \Magento\OfflineShipping\Model\ResourceModel\Carrier\Tablerate\CollectionFactory $collectionFactory
     * @param \Divante\Walkthechat\Service\ShippingZonesRepository                             $shippingZonesRepository
     */
    public function __construct(
        \Divante\Walkthechat\Helper\Data $helper,
        \Magento\OfflineShipping\Model\ResourceModel\Carrier\Tablerate\CollectionFactory $collectionFactory,
        \Divante\Walkthechat\Service\ShippingZonesRepository $shippingZonesRepository
    ) {
        $this->helper                  = $helper;
        $this->collectionFactory       = $collectionFactory;
        $this->shippingZonesRepository = $shippingZonesRepository;
    }

    /**
     * Sync Table Rates with Walkthechat
     *
     * @return bool
     */
    public function sync()
    {
        if ($this->helper->getTableRateConditionName() == 'package_weight') {
            $type = 'weight';
        } elseif ($this->helper->getTableRateConditionName() == 'package_value') {
            $type = 'price';
        } else {
            return false;
        }

        try {
            $rows = $this->shippingZonesRepository->find();

            foreach ($rows as $row) {
                if (isset($row['id'])) {
                    $this->shippingZonesRepository->delete($row['id']);
                }
            }

            /** @var \Magento\OfflineShipping\Model\ResourceModel\Carrier\Tablerate\Collection $collection */
            $collection = $this->collectionFactory->create();

            $collection->addFieldToFilter('condition_name', $this->helper->getTableRateConditionName());
            $collection->setOrder('condition_value', 'DESC');
            $collection->load();

            $rates = [];

            foreach ($collection as $row) {
                $rates[$row->getDestCountryId()][$row->getConditionValue()] = $row->getPrice();
            }

            foreach ($rates as $code => $rate) {
                $data = [
                    'name'      => [
                        'en' => $code,
                        'cn' => $code,
                    ],
                    'enabled'   => true,
                    'countries' => [$code],
                    'rates'     => [],
                ];

                $max = 999999999;
                foreach ($rate as $min => $value) {
                    $name = $code.' '.$min.'-'.$max;

                    $data['rates'][] = [
                        'name'   => [
                            'en' => $name,
                            'cn' => $name,
                        ],
                        'min'    => $min,
                        'max'    => $max,
                        'rate'   => $value,
                        'type'   => $type,
                        'isFree' => $value ? false : true,
                    ];
                }

                $this->shippingZonesRepository->create($data);
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
