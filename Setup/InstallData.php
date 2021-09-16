<?php
namespace Outshifter\Outshifter\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Integration\Model\ConfigBasedIntegrationManager;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;

class InstallData implements InstallDataInterface
{
    /**
     * @var ConfigBasedIntegrationManager
     */

    private $integrationManager;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @param ConfigBasedIntegrationManager $integrationManager
     */

    public function __construct(
      ConfigBasedIntegrationManager $integrationManager,
      EavSetupFactory $eavSetupFactory
    )
    {
        $this->integrationManager = $integrationManager;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->integrationManager->processIntegrationConfig(['Outshifter']);
        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->addAttribute(
          Product::ENTITY,
          'outshifter_exported',
          [
              'group' => 'General',
              'type' => 'int',
              'label' => 'Outshifter exported',
              'input' => 'boolean',
              'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
              'frontend' => '',
              'backend' => '',
              'required' => false,
              'sort_order' => 50,
              'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
              'is_used_in_grid' => true,
              'is_visible_in_grid' => false,
              'is_filterable_in_grid' => true,
              'visible' => true,
              'user_defined' => false,
              'is_html_allowed_on_front' => false,
              'visible_on_front' => false
          ]
      );
    }
}