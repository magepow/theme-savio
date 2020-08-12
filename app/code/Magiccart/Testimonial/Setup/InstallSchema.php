<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2016-03-28 16:22:16
 * @@Function:
 */

namespace Magiccart\Testimonial\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $table = $installer->getConnection()
            ->newTable($installer->getTable('magiccart_testimonial'))
            ->addColumn(
                'testimonial_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Testimonial ID'
            )
            ->addColumn('name', Table::TYPE_TEXT, 255, ['nullable' => false], 'Name')
            ->addColumn('image', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => null])
            ->addColumn('text', Table::TYPE_TEXT, '1M', [], 'product_ids')
            ->addColumn('company', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => null])
            ->addColumn('email', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => null])
            ->addColumn('website', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => null])
            ->addColumn('rating_summary', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '5'], 'Rating Summary')
            ->addColumn('stores', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => '0'])
            ->addColumn('order', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '0'], 'Order')
            ->addColumn('status', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Status')
            ->addColumn('created_time', Table::TYPE_DATETIME, null, ['nullable' => true, 'default' => null], 'Created Time')
            ->addColumn('update_time', Table::TYPE_DATETIME, null, ['nullable' => true, 'default' => null], 'Update Time')
            ->addIndex($installer->getIdxName('testimonial_id', ['testimonial_id']), ['testimonial_id'])
            ->setComment('Magiccart Testimonial');

        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }

}
