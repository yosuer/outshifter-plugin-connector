<?php
namespace Outshifter\PluginConnector\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Catalog\Model\Product;

class Uninstall implements UninstallInterface
{
	public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
	{
		$installer = $setup;
		$installer->startSetup();

    $eavSetup = $this->eavSetupFactory->create();   
    $eavSetup->removeAttribute(Product::ENTITY, 'outshifter_exported');   

		$installer->endSetup();
	}
}