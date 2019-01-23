<?php
/**
 * @package   Divante\Walkthechat
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\Walkthechat\Model;

/**
 * Class ImageSync
 *
 * @package Divante\Walkthechat\Model
 */
class ImageSync extends \Magento\Framework\Model\AbstractModel
    implements \Divante\Walkthechat\Api\Data\ImageSyncInterface
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Divante\Walkthechat\Model\ResourceModel\ImageSync::class);
    }

    /**
     * @inheritdoc}
     */
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * @inheritdoc}
     */
    public function setProductId($id)
    {
        return $this->setData(self::PRODUCT_ID, $id);
    }

    /**
     * @inheritdoc}
     */
    public function getImageId()
    {
        return $this->getData(self::IMAGE_ID);
    }

    /**
     * @inheritdoc}
     */
    public function setImageId($id)
    {
        return $this->setData(self::IMAGE_ID, $id);
    }

    /**
     * @inheritdoc}
     */
    public function getImageData()
    {
        return $this->getData(self::IMAGE_DATA);
    }

    /**
     * @inheritdoc}
     */
    public function setImageData($imageData)
    {
        return $this->setData(self::IMAGE_DATA, $imageData);
    }
}
