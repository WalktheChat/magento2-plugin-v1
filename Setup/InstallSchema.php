<?php
namespace Divante\Walkthechat\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Divante\Walkthechat\Api\Data\QueueInterface;
use Divante\Walkthechat\Model\ResourceModel\Queue as QueueResource;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (!$installer->tableExists(QueueResource::TABLE_NAME)) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable(QueueResource::TABLE_NAME))
                ->addColumn(
                    QueueInterface::ID,
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Entity Id'
                )->addColumn(
                    QueueInterface::PRODUCT_ID,
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['nullable' => true, 'unsigned' => true, 'default' => null],
                    'Magento Product Id'
                )->addColumn(
                    QueueInterface::ORDER_ID,
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['nullable' => true, 'unsigned' => true, 'default' => null],
                    'Magento Order Id'
                )->addColumn(
                    QueueInterface::WALKTHECHAT_ID,
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => true, 'default' => null],
                    'Walkthechat Id'
                )->addColumn(
                    QueueInterface::ACTION,
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    32,
                    ['nullable' => false, 'default' => 'add'],
                    'Action'
                )->addColumn(
                    QueueInterface::CREATED_AT,
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                    'Created At'
                )->addColumn(
                    QueueInterface::PROCESSED_AT,
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => true, 'default' => null],
                    'Processed At'
                )->addForeignKey(
                    $installer->getFkName(
                        QueueResource::TABLE_NAME,
                        QueueInterface::PRODUCT_ID,
                        'catalog_product_entity',
                        'entity_id'
                    ),
                    QueueInterface::PRODUCT_ID,
                    $installer->getTable('catalog_product_entity'),
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )->addForeignKey(
                    $installer->getFkName(
                        QueueResource::TABLE_NAME,
                        QueueInterface::ORDER_ID,
                        'sales_order',
                        'entity_id'
                    ),
                    QueueInterface::ORDER_ID,
                    $installer->getTable('sales_order'),
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )->setComment(
                    'Walkthechat Queue Table'
                );

            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}