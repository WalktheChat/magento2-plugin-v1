<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Alex Yeremenko <madonzy13@gmail.com>
 * @copyright 2019 WalktheChat
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Setup;

/**
 * Class InstallSchema
 *
 * @package Divante\Walkthechat\Setup
 */
class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws \Zend_Db_Exception
     */
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $installer = $setup;

        $installer->startSetup();

        $this->createWalkTheChatIdAttribute($installer);
        $this->createLogsTable($installer);
        $this->createQueueTable($installer);

        $installer->endSetup();
    }

    /**
     * Create table 'divante_walkthechat_logs'
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $installer
     *
     * @throws \Zend_Db_Exception
     */
    protected function createLogsTable(\Magento\Framework\Setup\SchemaSetupInterface $installer)
    {
        if (!$installer->tableExists(\Divante\Walkthechat\Model\ResourceModel\ApiLog::TABLE_NAME)) {
            $table = $installer
                ->getConnection()
                ->newTable($installer->getTable(\Divante\Walkthechat\Model\ResourceModel\ApiLog::TABLE_NAME))
                ->addColumn(
                    \Divante\Walkthechat\Api\Data\ApiLogInterface::ENTITY_ID_FIELD,
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary'  => true,
                    ],
                    'Entity ID'
                )
                ->addColumn(
                    \Divante\Walkthechat\Api\Data\ApiLogInterface::REQUEST_PATH_FIELD,
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [
                        'nullable' => false,
                    ],
                    'Request Path'
                )
                ->addColumn(
                    \Divante\Walkthechat\Api\Data\ApiLogInterface::REQUEST_METHOD_FIELD,
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    10,
                    [
                        'nullable' => false,
                    ],
                    'Response Method'
                )
                ->addColumn(
                    \Divante\Walkthechat\Api\Data\ApiLogInterface::REQUEST_PARAMS_FIELD,
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    [
                        'nullable' => true,
                    ],
                    'Request Params'
                )
                ->addColumn(
                    \Divante\Walkthechat\Api\Data\ApiLogInterface::RESPONSE_CODE_FIELD,
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => false,
                    ],
                    'Params'
                )
                ->addColumn(
                    \Divante\Walkthechat\Api\Data\ApiLogInterface::RESPONSE_DATA_FIELD,
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    [
                        'nullable' => true,
                    ],
                    'Params'
                )
                ->addColumn(
                    \Divante\Walkthechat\Api\Data\ApiLogInterface::IS_SUCCESS_RESPONSE_FIELD,
                    \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                    null,
                    [
                        'nullable' => false,
                    ],
                    'Is response successful'
                )
                ->addColumn(
                    \Divante\Walkthechat\Api\Data\ApiLogInterface::CREATED_AT_FIELD,
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'nullable' => false,
                        'default'  => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT,
                    ],
                    'Creation Time'
                )
                ->setComment('Walkthechat logs table');

            $installer->getConnection()->createTable($table);
        }
    }

    /**
     * Create table 'divante_walkthechat_queue'
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $installer
     *
     * @throws \Zend_Db_Exception
     */
    public function createQueueTable(\Magento\Framework\Setup\SchemaSetupInterface $installer)
    {
        if (!$installer->tableExists(\Divante\Walkthechat\Model\ResourceModel\Queue::TABLE_NAME)) {
            $table = $installer
                ->getConnection()
                ->newTable($installer->getTable(\Divante\Walkthechat\Model\ResourceModel\Queue::TABLE_NAME))
                ->addColumn(
                    \Divante\Walkthechat\Api\Data\QueueInterface::ID,
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary'  => true,
                    ],
                    'Entity Id'
                )
                ->addColumn(
                    \Divante\Walkthechat\Api\Data\QueueInterface::PRODUCT_ID,
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'nullable' => true,
                        'unsigned' => true,
                        'default'  => null,
                    ],
                    'Magento Product Id'
                )
                ->addColumn(
                    \Divante\Walkthechat\Api\Data\QueueInterface::ORDER_ID,
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'nullable' => true,
                        'unsigned' => true,
                        'default'  => null,
                    ],
                    'Magento Order Id'
                )
                ->addColumn(
                    \Divante\Walkthechat\Api\Data\QueueInterface::WALKTHECHAT_ID,
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [
                        'nullable' => true,
                        'default'  => null,
                    ],
                    'WalkTheChat Id'
                )
                ->addColumn(
                    \Divante\Walkthechat\Api\Data\QueueInterface::ACTION,
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    32,
                    [
                        'nullable' => false,
                        'default'  => 'add',
                    ],
                    'Action'
                )
                ->addColumn(
                    \Divante\Walkthechat\Api\Data\QueueInterface::CREATED_AT,
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'nullable' => false,
                        'default'  => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT,
                    ],
                    'Created At'
                )
                ->addColumn(
                    \Divante\Walkthechat\Api\Data\QueueInterface::PROCESSED_AT,
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'nullable' => true,
                        'default'  => null,
                    ],
                    'Processed At'
                )
                ->addForeignKey(
                    $installer->getFkName(
                        \Divante\Walkthechat\Model\ResourceModel\Queue::TABLE_NAME,
                        \Divante\Walkthechat\Api\Data\QueueInterface::PRODUCT_ID,
                        'catalog_product_entity',
                        'entity_id'
                    ),
                    \Divante\Walkthechat\Api\Data\QueueInterface::PRODUCT_ID,
                    $installer->getTable('catalog_product_entity'),
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $installer->getFkName(
                        \Divante\Walkthechat\Model\ResourceModel\Queue::TABLE_NAME,
                        \Divante\Walkthechat\Api\Data\QueueInterface::ORDER_ID,
                        'sales_order',
                        'entity_id'
                    ),
                    \Divante\Walkthechat\Api\Data\QueueInterface::ORDER_ID,
                    $installer->getTable('sales_order'),
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )
                ->setComment('WalkTheChat Queue Table');

            $installer->getConnection()->createTable($table);
        }
    }

    /**
     * Create new column in order table
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $installer
     */
    protected function createWalkTheChatIdAttribute(\Magento\Framework\Setup\SchemaSetupInterface $installer)
    {
        $connection = $installer->getConnection();

        if (!$connection->tableColumnExists('sales_order', \Divante\Walkthechat\Helper\Data::ATTRIBUTE_CODE)) {
            $connection
                ->addColumn(
                    $connection->getTableName('sales_order'),
                    \Divante\Walkthechat\Helper\Data::ATTRIBUTE_CODE,
                    [
                        'type'    => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length'  => 255,
                        'comment' => 'WalkTheChat ID',
                    ]
                );
        }
    }
}
