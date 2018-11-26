<?php
/**
 * @package   Divante\Walkthechat
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
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
     */
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $installer = $setup;

        $installer->startSetup();

        $this->createLogsTable($installer);

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
        $table = $installer
            ->getConnection()
            ->newTable($installer->getTable(\Divante\Walkthechat\Model\ResourceModel\ApiLog::MAIN_TABLE))
            ->addColumn(
                \Divante\Walkthechat\Api\Data\ApiLogInterface::ENTITY_ID_FIELD,
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
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
                    'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT
                ],
                'Creation Time'
            )
            ->setComment('Walkthechat logs table');

        $installer->getConnection()->createTable($table);
    }
}
