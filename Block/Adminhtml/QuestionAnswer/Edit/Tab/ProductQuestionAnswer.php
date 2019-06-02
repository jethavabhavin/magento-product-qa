<?php
/**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Block\Adminhtml\QuestionAnswer\Edit\Tab;

use Bhavin\ProductQA\Model\Source\Status;
use Magento\Backend\Block\Template\Context;
use Magento\Cms\Ui\Component\Listing\Column\Cms\Options;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;

class QuestionAnswer extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface {
	const FORM_NAME = 'bhavin_productqa_question_form';
	/**
	 * Enable / Disable options
	 */
	protected $_status;
	/**
	 * Horizintal / Virticle options
	 */
	protected $_orientation;
	/**
	 * Store View options
	 */
	protected $_cmsOpt;
	/**
	 * constructor
	 *
	 * @param Context $context
	 * @param Registry $registry
	 * @param FormFactory $formFactory
	 * @param array $data
	 */
	public function __construct(
		Context $context,
		Registry $registry,
		FormFactory $formFactory,
		Status $status,
		Options $cmsOpt,
		array $data = []
	) {
		$this->_cmsOpt = $cmsOpt;

		$this->_status = $status;

		parent::__construct($context, $registry, $formFactory, $data);
	}
	/**
	 * Prepare form
	 *
	 * @return $this
	 */
	protected function _prepareForm() {

		/** @var \Bhavin\ProductQA\Model\Question $product_question */
		$product_question = $this->_coreRegistry->registry('bhavin_product_questionanswer');

		$form = $this->_formFactory->create();

		$form->setHtmlIdPrefix('product_questionanswer_');
		$form->setFieldNameSuffix('product_questionanswer');

		$fieldset = $form->addFieldset(
			'base_fieldset',
			[
				'legend' => __('Setting'),
				'class' => 'fieldset-wide',
			]
		);

		//$fieldset->addType('customer', 'Bhavin\ProductQA\Block\Adminhtml\QuestionAnswer\Edit\Tab\Renderer\CustomerColumn');

		if ($product_question->getId()) {
			$fieldset->addField(
				'id',
				'hidden',
				['name' => 'id']
			);
		}

		$fieldset->addField(
			'status',
			'select',
			[
				'name' => 'status',
				'label' => __('Status'),
				'title' => __('Status'),
				'required' => true,
				'values' => $this->_status->toOptionArray(),
			]
		);
		$fieldset->addField(
			'answer',
			'editor',
			[
				'name' => 'answer',
				'label' => __('Answer'),
				'title' => __('Answer'),
				'required' => true,
			]
		);

		$fieldset->addField(
			'name',
			'text',
			[
				'name' => 'name',
				'label' => __('Customer Name'),
				'title' => __('Customer Name'),
				'renderer' => Renderer\CustomerColumn::class,
				'required' => true,
			]
		);

		$fieldset->addField(
			'email',
			'text',
			[
				'name' => 'email',
				'label' => __('Customer Email'),
				'title' => __('Customer Email'),
				'required' => true,
			]
		);

		$product_questionData = $this->_session->getData('bhavin_productqa_product_questionanswer_data', true);

		if ($product_questionData) {
			$product_question->addData($product_questionData);
		} else {
			if (!$product_question->getId()) {
				$product_question->addData($product_question->getDefaultValues());
			}
		}

		$form->addValues($product_question->getData());

		$this->setForm($form);

		return parent::_prepareForm();
	}

	/**
	 * Prepare Question for tab
	 *
	 * @return string
	 */
	public function getTabLabel() {
		return __('General');
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
