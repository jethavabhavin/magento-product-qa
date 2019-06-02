<?php
 /**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */

namespace Bhavin\ProductQA\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Store\Model\StoreManagerInterface;

class InstallSchema implements InstallSchemaInterface
{
	public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();
		
		/*Product Question Table*/
		
        $table  = $installer->getConnection()
            ->newTable($installer->getTable('bhavin_product_question'))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'unique primary Id'
            )
			->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => null],
                'reference product id'
            )
			->addColumn(
                'user_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => null],
                'reference user id who create record'
            )
            ->addColumn(
                'user_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                1,
                ['default' => null],
                'user type [customer/admin ]'
            )
			->addColumn(
                'ask_by',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => null],
                'ask_by'
            )
			->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'Customer name '
            )
			
			->addColumn(
                'email',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'customer email'
            )
            ->addColumn(
                'question',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['default' => null],
                'Product Question'
            )
			->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                1,
                [],
                'Store Id'
            )
			->addColumn(
                'likes',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => 0],
                'likes count'
            )
			->addColumn(
                'dislikes',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => 0],
                'dislike count'
            )
			->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                1,
                [],
                'Status'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Product Question  Created At'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Product Question Updated At'
            )
			->addIndex(  
				$installer->getIdxName(  
					$installer->getTable('bhavin_product_question'),  
					['question','name','email'],  
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT  
				),  
				['question','name','email'],
				['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT]
			);
		
		$installer->getConnection()->createTable($table);
		
		/*Product Question Table*/
		
        $table  = $installer->getConnection()
            ->newTable($installer->getTable('bhavin_productanswer'))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'unique primary Id'
            )
			->addColumn(
                'question_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => null],
                'reference question id'
            )
			->addColumn(
                'user_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => null],
                'reference user id who create record'
            )
            ->addColumn(
                'user_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                1,
                ['default' => null],
                'user type [customer/admin]'
            )
			->addColumn(
                'answer_by',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => null],
                'answer_by'
            )
			->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'Customer name '
            )
			
			->addColumn(
                'email',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'customer email'
            )
            ->addColumn(
                'answer',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['default' => null, 'nullable' => false],
                'Product Question Answer'
            )
			->addColumn(
                'likes',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => 0],
                'likes count'
            )
			->addColumn(
                'dislikes',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => 0],
                'dislike count'
            )
			->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                1,
                [],
                'Status'
            )
			->addColumn(
                'mail_send_status',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                1,
                [],
                'Mail Send Status'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Product Question  Created At'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Product Question Updated At'
            )
			->addIndex(  
				$installer->getIdxName(  
					$installer->getTable('bhavin_productanswer'),  
					['answer','name','email'],  
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT  
				),  
				['answer','name','email'],
				['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT]
			);
			
        $installer->getConnection()->createTable($table);
		
			
		/*Product Question Table*/
		
        $table  = $installer->getConnection()
            ->newTable($installer->getTable('bhavin_qa_action'))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'unique primary Id'
            )
			->addColumn(
                'object_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => null],
                'reference question id or answer id'
            )
			->addColumn(
                'object_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                50,
                ['default' => null],
                'reference question id or answer id'
            )
			->addColumn(
                'action_by',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => null],
                'Customer id'
            )
			->addColumn(
                'action',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                1,
                ['default' => null],
                'Action like = 1, dislike = 0 '
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Product Question  Created At'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Product Question Updated At'
            );
		
		$installer->getConnection()->createTable($table);
		
		$installer->endSetup();
		
    }
}
