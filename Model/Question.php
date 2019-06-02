<?php
/**
 * @category  Bhavin Product Question Answser
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */

namespace Bhavin\ProductQA\Model;

class Question extends \Magento\Framework\Model\AbstractModel {
	/**
	 * Model Initialization
	 *
	 * @return void
	 */
	protected function _construct() {
		parent::_construct();

		$this->_init('Bhavin\ProductQA\Model\ResourceModel\Question');
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
		$answers = $objectManager->create('Bhavin\ProductQA\Model\ResourceModel\QuestionAnswer\Collection');
		$answers->addFieldToFilter("question_id", $this->getId());
		foreach ($answers as $answer) {
			$answer->delete();
		}

		$actions = $objectManager->create('Bhavin\ProductQA\Model\ResourceModel\ProductqaAction\Collection');
		$actions->addFieldToFilter("object_id", $this->getId());
		$actions->addFieldToFilter("object_type", 'question');
		foreach ($actions as $action) {
			$action->delete();
		}

		return $this;
	}
}
