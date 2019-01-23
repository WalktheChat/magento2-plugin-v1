<?php
/**
 * @package   Divante\Walkthechat
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\Walkthechat\Api\Data;

/**
 * Interface ImageSyncInterface
 *
 * @package Divante\Walkthechat\Api\Data
 */
interface ImageSyncInterface
{
    /**@#+
     * Fields
     */
    const ID         = 'entity_id';
    const PRODUCT_ID = 'product_id';
    const IMAGE_ID   = 'image_id';
    const IMAGE_DATA = 'image_data';
    /**@#- */

    /**
     * Return ID
     *
     * @return int
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id
     *
     * @return \Divante\Walkthechat\Api\Data\ImageSyncInterface
     */
    public function setId($id);

    /**
     * Return product ID
     *
     * @return int
     */
    public function getProductId();

    /**
     * Set product ID
     *
     * @param int $id
     *
     * @return \Divante\Walkthechat\Api\Data\ImageSyncInterface
     */
    public function setProductId($id);

    /**
     * Return image ID
     *
     * @return int
     */
    public function getImageId();

    /**
     * Set image ID
     *
     * @param int $id
     *
     * @return \Divante\Walkthechat\Api\Data\ImageSyncInterface
     */
    public function setImageId($id);

    /**
     * Return image data
     *
     * @return string
     */
    public function getImageData();

    /**
     * Set image data
     *
     * @param string $imageData
     *
     * @return \Divante\Walkthechat\Api\Data\ImageSyncInterface
     */
    public function setImageData($imageData);
}
