<?php
 /**
 * @category  Bhavin Product Question Answser
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */

namespace Bhavin\ProductQA\Model\ResourceModel\ProductQuestionAnswer;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	 /**
     * ID Field Name
     * 
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Bhavin\ProductQA\Model\ProductQuestionAnswer', 'Bhavin\ProductQA\Model\ResourceModel\ProductQuestionAnswer');
    }
	
    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @return \Magento\Framework\DB\Select
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
		
        $countSelect->reset(\Zend_Db_Select::GROUP);
		
        return $countSelect;
    }
    /**
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     */
    protected function _toOptionArray($valueField = 'id', $labelField = 'answer', $additional = [])
    {
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }
	
}