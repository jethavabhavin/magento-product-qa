<?php
/**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Block\Adminhtml;

class ProductQuestion extends \Magento\Backend\Block\Widget\Grid\Container {
	/**
	 * constructor
	 *
	 * @return void
	 */
	protected function _construct() {
		$this->_controller = 'adminhtml_product_question';

		$this->_blockGroup = 'Bhavin_ProductQA';

		$this->_headerText = __('Question');

		$this->_addButtonLabel = __('Add Product Question');

		parent::_construct();
	}
}
