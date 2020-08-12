<?php
namespace Magiccart\Testimonial\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
	public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
		$installer = $setup;

		$installer->startSetup();

		if(version_compare($context->getVersion(), '2.1', '<')) {
			$installer->getConnection()->addColumn(
				$installer->getTable( 'magiccart_testimonial' ),
				'job',
				[
					'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					'nullable' => true,
					'length' => '255',
					'comment' => 'Job',
					'after' => 'company'
				]
			);
		}
		$installer->endSetup();
	}
}