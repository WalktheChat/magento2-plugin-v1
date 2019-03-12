<?php
/**
 * @package   Divante\Walkthechat
 * @author    Alex Yeremenko <madonzy13@gmail.com>
 * @copyright 2019 WalktheChat
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\Walkthechat\Model\Config\Source;

/**
 * Class QueueItemStatus
 *
 * @package Divante\Walkthechat\Model\Config\Source
 */
class QueueItemStatus implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => \Divante\Walkthechat\Api\Data\QueueInterface::INTERNAL_ERROR_STATUS,
                'label' => __('Internal Error'),
            ],
            ['value' => \Divante\Walkthechat\Api\Data\QueueInterface::API_ERROR_STATUS, 'label' => __('API Error')],
            ['value' => \Divante\Walkthechat\Api\Data\QueueInterface::COMPLETE_STATUS, 'label' => __('Complete')],
            [
                'value' => \Divante\Walkthechat\Api\Data\QueueInterface::WAITING_IN_QUEUE_STATUS,
                'label' => __('Waiting in Queue'),
            ],
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            \Divante\Walkthechat\Api\Data\QueueInterface::WAITING_IN_QUEUE_STATUS => __('Waiting in Queue'),
            \Divante\Walkthechat\Api\Data\QueueInterface::COMPLETE_STATUS         => __('Complete'),
            \Divante\Walkthechat\Api\Data\QueueInterface::API_ERROR_STATUS        => __('API Error'),
            \Divante\Walkthechat\Api\Data\QueueInterface::INTERNAL_ERROR_STATUS   => __('Internal Error'),
        ];
    }
}
