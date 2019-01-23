<?php
/**
 * @package   Divante\Walkthechat
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\Walkthechat\Setup;

/**
 * Class UpgradeSchema
 *
 * @package Divante\Walkthechat\Setup
 */
class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(
        \Magento\Framework\Setup\SchemaSetupInterface $installer,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        if (version_compare($context->getVersion(), '0.2.0', '<')) {
            $this->createImageSyncTable($installer);
        }
    }

    /**
     * Creates image sync table
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $installer
     *
     * @return $this
     * @throws \Zend_Db_Exception
     */
    protected function createImageSyncTable(\Magento\Framework\Setup\SchemaSetupInterface $installer)
    {
        if (!$installer->tableExists(\Divante\Walkthechat\Model\ResourceModel\ImageSync::TABLE_NAME)) {
            $table = $installer
                ->getConnection()
                ->newTable($installer->getTable(\Divante\Walkthechat\Model\ResourceModel\ImageSync::TABLE_NAME))
                ->addColumn(
                    \Divante\Walkthechat\Api\Data\ImageSyncInterface::ID,
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
                    \Divante\Walkthechat\Api\Data\ImageSyncInterface::PRODUCT_ID,
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
                    \Divante\Walkthechat\Api\Data\ImageSyncInterface::IMAGE_ID,
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'nullable' => true,
                        'unsigned' => true,
                        'default'  => null,
                    ],
                    'Image Product Id'
                )
                ->addColumn(
                    \Divante\Walkthechat\Api\Data\ImageSyncInterface::IMAGE_DATA,
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    [
                        'nullable' => false,
                    ],
                    'Image Data'
                )
                ->addForeignKey(
                    $installer->getFkName(
                        \Divante\Walkthechat\Model\ResourceModel\ImageSync::TABLE_NAME,
                        \Divante\Walkthechat\Api\Data\ImageSyncInterface::PRODUCT_ID,
                        'catalog_product_entity',
                        'entity_id'
                    ),
                    \Divante\Walkthechat\Api\Data\ImageSyncInterface::PRODUCT_ID,
                    $installer->getTable('catalog_product_entity'),
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $installer->getFkName(
                        \Divante\Walkthechat\Model\ResourceModel\ImageSync::TABLE_NAME,
                        \Divante\Walkthechat\Api\Data\ImageSyncInterface::IMAGE_ID,
                        'catalog_product_entity_media_gallery',
                        'value_id'
                    ),
                    \Divante\Walkthechat\Api\Data\ImageSyncInterface::IMAGE_ID,
                    $installer->getTable('catalog_product_entity_media_gallery'),
                    'value_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )
                ->addIndex(
                    $installer->getIdxName(
                        \Divante\Walkthechat\Model\ResourceModel\ImageSync::TABLE_NAME,
                        [
                            \Divante\Walkthechat\Api\Data\ImageSyncInterface::PRODUCT_ID,
                            \Divante\Walkthechat\Api\Data\ImageSyncInterface::IMAGE_ID
                        ],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                    ),
                    [
                        \Divante\Walkthechat\Api\Data\ImageSyncInterface::PRODUCT_ID,
                        \Divante\Walkthechat\Api\Data\ImageSyncInterface::IMAGE_ID
                    ],
                    ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
                )
                ->setComment('Walkthechat image synchronization table');

            $installer->getConnection()->createTable($table);
        }

        return $this;
    }
}
