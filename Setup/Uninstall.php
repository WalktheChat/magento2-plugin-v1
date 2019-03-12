<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Alex Yeremenko <madonzy13@gmail.com>
 * @copyright 2018 WalktheChat
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Setup;

/**
 * Class Uninstall
 *
 * @package Divante\Walkthechat\Setup
 */
class Uninstall implements \Magento\Framework\Setup\UninstallInterface
{
    /**
     * EAV setup factory
     *
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * Init
     *
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     */
    public function __construct(\Magento\Eav\Setup\EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function uninstall(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $setup->startSetup();

        /** @var \Magento\Eav\Setup\EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        // remove walkthechat_id attribute from product eav entity
        $eavSetup->removeAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            \Divante\Walkthechat\Helper\Data::ATTRIBUTE_CODE
        );

        // drop module tables
        $setup->getConnection()->dropTable(\Divante\Walkthechat\Model\ResourceModel\ApiLog::TABLE_NAME);
        $setup->getConnection()->dropTable(\Divante\Walkthechat\Model\ResourceModel\Queue::TABLE_NAME);
        $setup->getConnection()->dropTable(\Divante\Walkthechat\Model\ResourceModel\ImageSync::TABLE_NAME);

        // drop module integrated columns
        $setup->getConnection()->dropColumn('sales_order', \Divante\Walkthechat\Helper\Data::ATTRIBUTE_CODE);
        $setup->getConnection()->dropColumn('sales_order_item', 'walkthechat_item_data');
        $setup->getConnection()->dropColumn('sales_shipment', 'is_sent_to_walk_the_chat');
        $setup->getConnection()->dropColumn('sales_creditmemo', 'is_sent_to_walk_the_chat');

        $setup->endSetup();
    }
}
