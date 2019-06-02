<?php
/**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Block\Adminhtml\ProductQuestion\Edit\Tab;

use \Magento\Framework\Registry;

class QuestionAnswer extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface {
	/**
	 * Prepare form
	 *
	 * @return $this
	 */
	protected function _prepareForm() {
		/** @var \Bhavin\ProductQA\Model\ProductQuestion $product_question */
		$product_question = $this->_coreRegistry->registry('bhavin_product_question');

		$form = $this->_formFactory->create();

		$form->setHtmlIdPrefix('answers_');
		$form->setFieldNameSuffix('answers');

		$fieldset = $form->addFieldset(
			'base_fieldset',
			[
				'legend' => __('Answers'),
				'class' => 'fieldset-wide',
			]
		);

		$answerBlock = $this->getLayout()->createBlock(QAnswer::class)->setQuestionId($product_question->getId())->setStoreId($product_question->getStoreId());

		$fieldset->addField(
			'answer_value_container',
			'note',
			[
				'text' => $answerBlock->toHtml(),
			]
		);

		$this->setForm($form);

		return parent::_prepareForm();
	}

	/**
	 * Prepare Sizeadviser for tab
	 *
	 * @return string
	 */
	public function getTabLabel() {
		return __('Answers');
	}

	/**
	 * Prepare title for tab
	 *
	 * @return string
	 */
	public function getTabTitle() {
		return $this->getTabLabel();
	}

	/**
	 * Can show tab in tabs
	 *
	 * @return boolean
	 */
	public function canShowTab() {
		return true;
	}

	/**
	 * Tab is hidden
	 *
	 * @return boolean
	 */
	public function isHidden() {
		return false;
	}
}
