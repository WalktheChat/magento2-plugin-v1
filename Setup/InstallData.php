<?php
namespace Divante\Walkthechat\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Setup\EavSetup;

/**
 * Walkthechat InstallData
 *
 * @package  Divante\Walkthechat
 * @author   Divante Tech Team <tech@divante.pl>
 */
class InstallData implements InstallDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * Install new Swatch entity
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $entities = [
            \Magento\Catalog\Model\Product::ENTITY,
            \Magento\Sales\Model\Order::ENTITY
        ];

        foreach ($entities as $entity) {
            $eavSetup->addAttribute(
                $entity,
                'walkthechat_id',
                [
                    'type' => 'varchar',
                    'label' => 'Walkthechat ID',
                    'input' => 'text',
                    'required' => false,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'used_in_product_listing' => false,
                    'unique' => true,
                    'visible' => false
                ]
            );
        }
    }
}
