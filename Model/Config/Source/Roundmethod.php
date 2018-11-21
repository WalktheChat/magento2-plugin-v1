<?php
namespace Divante\Walkthechat\Model\Config\Source;

/**
 * Walkthechat Round methods
 *
 * @package  Divante\Walkthechat
 * @author   Divante Tech Team <tech@divante.pl>
 */
class Roundmethod implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 1,  'label' => __('Nearest Integer')],
            ['value' => 2,  'label' => __('China friendly price')],
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [1 => __('Nearest Integer'), 2 => __('China friendly price')];
    }
}
