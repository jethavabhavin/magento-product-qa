<?php
/**
 * @category  Bhavin Product Question Answser
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Model;

class QuestionAnswer extends \Magento\Framework\Model\AbstractModel {
	/**
	 * Model Initialization
	 *
	 * @return void
	 */
	protected function _construct() {
		parent::_construct();

		$this->_init('Bhavin\ProductQA\Model\ResourceModel\QuestionAnswer');
	}

	/**
	 * @return mixed
	 */
	public function getDefaultValues() {
		$values = [];

		return $values;
	}
	/**
	 * Processing object before delete data
	 *
	 * @return $this
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function beforeDelete() {
		parent::beforeDelete();
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

		$actions = $objectManager->get('Bhavin\ProductQA\Model\ResourceModel\ProductqaAction\Collection');
		$actions->addFieldToFilter("object_id", $this->getId());
		$actions->addFieldToFilter("object_type", 'answer');
		foreach ($actions as $action) {
			$action->delete();
		}

		return $this;
	}
}
