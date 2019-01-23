<?php
/**
 * @package   Divante\Walkthechat
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\Walkthechat\Model;

/**
 * Class ActionFactory
 *
 * @package Divante\Walkthechat\Model
 */
class ActionFactory
{
    /**
     * Allowed action
     *
     * @var array
     */
    const ALLOWED_ACTIONS = [
        \Divante\Walkthechat\Model\Action\Add::ACTION,
        \Divante\Walkthechat\Model\Action\Update::ACTION,
        \Divante\Walkthechat\Model\Action\Delete::ACTION,
    ];

    /**
     * Action namespace
     *
     * @string
     */
    const ACTION_NAMESPACE = '\Divante\Walkthechat\Model\Action\\';

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * ActionFactory constructor.
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

    /**
     * @param string $action
     *
     * @return \Divante\Walkthechat\Model\Action\AbstractAction
     * @throws \Exception
     */
    public function create($action)
    {
        if (in_array($action, static::ALLOWED_ACTIONS)) {
            $class = static::ACTION_NAMESPACE.ucfirst(strtolower($action));

            if (class_exists($class)) {
                return $this->objectManager->create($class);
            }
        }

        throw new \Exception(__('Unable to load action. Undefined action "%1".'));
    }
}
