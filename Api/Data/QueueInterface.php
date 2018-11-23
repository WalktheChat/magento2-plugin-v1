<?php
namespace Divante\Walkthechat\Api\Data;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */
interface QueueInterface
{
    const ID = 'entity_id';

    const PRODUCT_ID = 'product_id';

    const ORDER_ID = 'order_id';

    const WALKTHECHAT_ID = 'walkthechat_id';

    const ACTION = 'action';

    const CREATED_AT = 'created_at';

    const PROCESSED_AT = 'processed_at';

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return void
     */
    public function setId($id);
}
