<?php
namespace Divante\Walkthechat\Api\Data;

/**
 * Walkthechat Queue Interface
 *
 * @package  Divante\Walkthechat
 * @author   Divante Tech Team <tech@divante.pl>
 */
interface QueueInterface
{
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
