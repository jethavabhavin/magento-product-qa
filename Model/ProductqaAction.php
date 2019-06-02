<?php
/**
 * @category  Bhavin Product Question Answser
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */

namespace Bhavin\ProductQA\Model;

class ProductqaAction extends \Magento\Framework\Model\AbstractModel {
	/**
	 * Model Initialization
	 *
	 * @return void
	 */
	protected function _construct() {
		parent::_construct();

		$this->_init('Bhavin\ProductQA\Model\ResourceModel\ProductqaAction');
	}

	/**
	 * @return mixed
	 */
	public function getDefaultValues() {
		$values = [];

		return $values;
	}
}
