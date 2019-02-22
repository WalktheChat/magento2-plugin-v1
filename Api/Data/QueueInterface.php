<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Api\Data;

/**
 * Interface QueueInterface
 *
 * @package Divante\Walkthechat\Api\Data
 */
interface QueueInterface
{
    /**@#+
     * Fields
     */
    const ID             = 'entity_id';
    const PRODUCT_ID     = 'product_id';
    const ORDER_ID       = 'order_id';
    const WALKTHECHAT_ID = 'walkthechat_id';
    const ACTION         = 'action';
    const CREATED_AT     = 'created_at';
    const PROCESSED_AT   = 'processed_at';
    /**@#- */

    /**
     * Return entity_id
     *
     * @return int
     */
    public function getId();

    /**
     * Set entity_id
     *
     * @param int $id
     *
     * @return \Divante\Walkthechat\Api\Data\QueueInterface
     */
    public function setId($id);

    /**
     * Return product_id
     *
     * @return int
     */
    public function getProductId();

    /**
     * Set product_id
     *
     * @param int $id
     *
     * @return \Divante\Walkthechat\Api\Data\QueueInterface
     */
    public function setProductId($id);

    /**
     * Return order_id
     *
     * @return int
     */
    public function getOrderId();

    /**
     * Set order_id
     *
     * @param int $id
     *
     * @return \Divante\Walkthechat\Api\Data\QueueInterface
     */
    public function setOrderId($id);

    /**
     * Return walkthechat_id
     *
     * @return int
     */
    public function getWalkthechatId();

    /**
     * Set walkthechat_id
     *
     * @param int $id
     *
     * @return \Divante\Walkthechat\Api\Data\QueueInterface
     */
    public function setWalkthechatId($id);

    /**
     * Return action
     *
     * @return string
     */
    public function getAction();

    /**
     * Set action
     *
     * @param string $action
     *
     * @return \Divante\Walkthechat\Api\Data\QueueInterface
     */
    public function setAction($action);

    /**
     * Return created_at
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set created_at
     *
     * @param string $gsmDate
     *
     * @return \Divante\Walkthechat\Api\Data\QueueInterface
     */
    public function setCreatedAt($gsmDate);

    /**
     * Return processed_at
     *
     * @return string
     */
    public function getProcessedAt();

    /**
     * Set processed_at
     *
     * @param string $gsmDate
     *
     * @return \Divante\Walkthechat\Api\Data\QueueInterface
     */
    public function setProcessedAt($gsmDate);
}
