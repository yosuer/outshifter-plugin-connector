<?php
namespace Outshifter\PluginConnector\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetupFactory;

class Uninstall implements UninstallInterface
{

  protected $eavSetupFactory;

  public function __construct(EavSetupFactory $eavSetupFactory)
  {
      $this->eavSetupFactory = $eavSetupFactory;
  }

	public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
	{
		$installer = $setup;
		$installer->startSetup();

    $eavSetup = $this->eavSetupFactory->create();   
    $eavSetup->removeAttribute(Product::ENTITY, 'outshifter_exported');   

		$installer->endSetup();
	}
}