<?php
/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\Walkthechat\Service;

/**
 * Class OrdersRepository
 *
 * @package Divante\Walkthechat\Service
 */
class OrdersRepository extends AbstractService
{
    /**
     * @var Resource\Orders\Update
     */
    protected $orderUpdateResource;

    /**
     * @var Resource\Orders\Parcels\Create
     */
    protected $orderParcelCreateResource;

    /**
     * {@inheritdoc}
     *
     * @param Resource\Orders\Update         $orderUpdateResource
     * @param Resource\Orders\Parcels\Create $orderParcelCreateResource
     */
    public function __construct(
        \Divante\Walkthechat\Service\Client $serviceClient,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Divante\Walkthechat\Helper\Data $helper,
        \Divante\Walkthechat\Log\ApiLogger $logger,
        Resource\Orders\Update $orderUpdateResource,
        Resource\Orders\Parcels\Create $orderParcelCreateResource
    ) {
        $this->orderUpdateResource       = $orderUpdateResource;
        $this->orderParcelCreateResource = $orderParcelCreateResource;

        parent::__construct(
            $serviceClient,
            $jsonHelper,
            $helper,
            $logger
        );
    }

    /**
     * Update order in Walkthechat
     *
     * @param $data
     *
     * @throws \Magento\Framework\Exception\CronException
     * @throws \Zend_Http_Client_Exception
     */
    public function update($data)
    {
        if (isset($data['status'])) {
            // TO-DO missing status update method in API
            //$this->request($this->orderUpdateResource, $data);
        }

        if (isset($data['parcels']) && is_array($data['parcels'])) {
            foreach ($data['parcels'] as $parcel) {
                $parcel['id'] = $data['id'];
                $this->request($this->orderParcelCreateResource, $parcel);
            }
        }
    }
}
