<?php
 /**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Block\Adminhtml\ProductQuestion\Edit\Tab\Renderer;

use \Magento\Framework\App\ObjectManager;

/**
 * @method string getValue()
 */
class CustomerColumn extends \Magento\Framework\Data\Form\Element\Text
{
	
    /**
     * Get customer url
     *
     * @return string
     */
    public function getAfterElementHtml()
    {
		 $objectManager = ObjectManager::getInstance();
		
		$registry = $objectManager->create('Magento\Framework\Registry');
		
		$product_question = $registry->registry('bhavin_product_question');
		/* echo "test";
		echo parent::getAfterElementHtml();
		 print_r($product_question);exit;
		 print_r(get_class($product_question));exit; */
		 
        return parent::getAfterElementHtml()."test123";
    }
}
