<?php
/**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Block\Adminhtml\QuestionAnswer\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic {
	/**
	 * Prepare form
	 *
	 * @return $this
	 */
	protected function _prepareForm() {
		/** @var \Magento\Framework\Data\Form $form */
		$form = $this->_formFactory->create(
			[
				'data' => [
					'id' => 'edit_form',
					'action' => $this->getData('action'),
					'method' => 'post',
					'enctype' => 'multipart/form-data',
				],
			]
		);

		$form->setUseContainer(true);

		$this->setForm($form);

		return parent::_prepareForm();
	}
}
