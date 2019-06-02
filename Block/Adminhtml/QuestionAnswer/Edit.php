<?php
/**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Block\Adminhtml\QuestionAnswer;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;

class Edit extends \Magento\Backend\Block\Widget\Form\Container {
	/**
	 * Core registry
	 *
	 * @var \Magento\Framework\Registry
	 */
	protected $_coreRegistry;

	/**
	 * constructor
	 *
	 * @param Registry $coreRegistry
	 * @param Context $context
	 * @param array $data
	 */
	public function __construct(
		Registry $coreRegistry,
		Context $context,
		array $data = []
	) {
		$this->_coreRegistry = $coreRegistry;

		parent::__construct($context, $data);
	}

	/**
	 * Initialize Post edit block
	 *
	 * @return void
	 */
	protected function _construct() {
		$this->_objectId = 'id';

		$this->_blockGroup = 'Bhavin_ProductQA';

		$this->_controller = 'adminhtml_productQuestion';

		parent::_construct();

		$this->buttonList->update('save', 'label', __('Save Product Question'));

		$this->buttonList->add(
			'save-and-continue',
			[
				'label' => __('Save and Continue Edit'),
				'class' => 'save',
				'data_attribute' => [
					'mage-init' => [
						'button' => [
							'event' => 'saveAndContinueEdit',
							'target' => '#edit_form',
						],
					],
				],
			],
			-100
		);
	}

	/**
	 * Retrieve text for header element depending on loaded Slider
	 *
	 * @return string
	 */
	public function getHeaderText() {
		$product_question = $this->_coreRegistry->registry('bhavin_product_questionanswer');

		if ($product_question->getId()) {
			return __("Edit Template '%1'", $this->escapeHtml($product_question->getName()));
		}

		return __('New  Product Question');
	}
}
