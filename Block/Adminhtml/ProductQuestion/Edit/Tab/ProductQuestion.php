<?php
/**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Block\Adminhtml\ProductQuestion\Edit\Tab;

use \Bhavin\ProductQA\Model\Source\Status;
use \Magento\Backend\Block\Template\Context;
use \Magento\Cms\Ui\Component\Listing\Column\Cms\Options;
use \Magento\Framework\Data\FormFactory;
use \Magento\Framework\Registry;

class ProductQuestion extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface {
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

		/** @var \Bhavin\ProductQA\Model\ProductQuestion $product_question */
		$product_question = $this->_coreRegistry->registry('bhavin_product_question');

		$form = $this->_formFactory->create();

		$form->setHtmlIdPrefix('product_question_');
		$form->setFieldNameSuffix('product_question');

		$fieldset = $form->addFieldset(
			'base_fieldset',
			[
				'legend' => __('Setting'),
				'class' => 'fieldset-wide',
			]
		);

		//$fieldset->addType('customer', 'Bhavin\ProductQA\Block\Adminhtml\ProductQuestion\Edit\Tab\Renderer\CustomerColumn');

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

		$fieldset->addField(
			'question',
			'textarea',
			[
				'name' => 'question',
				'label' => __('Question'),
				'title' => __('Question'),
				'required' => true,
			]
		);
		$fieldset->addField(
			'store_id',
			'select',
			[
				'name' => 'store_id',
				'label' => __('Store View'),
				'title' => __('Store View'),
				'required' => true,
				'values' => $this->_cmsOpt->toOptionArray(),
			]
		);

		$product_questionData = $this->_session->getData('bhavin_pdfinvoice_pdftemplate_data', true);

		if ($product_questionData) {
			$product_question->addData($product_questionData);
		} else {
			if (!$product_question->getId()) {
				$product_question->addData($product_question->getDefaultValues());
			}
		}

		$form->addValues($product_question->getData());

		$form = $this->addProductFieldset($form, $product_question);

		$this->setForm($form);

		return parent::_prepareForm();
	}

	/**
	 * Add Product fieldset
	 *
	 * @param \Magento\Framework\Data\Form $form
	 * @param array $formData
	 * @return \Magento\Framework\Data\Form
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	private function addProductFieldset($form, $question) {
		$productBlock = $this->getLayout()->createBlock(
			ProductGrid::class,
			null,
			['data' => ['product_ids' => explode(',', $question->getProductId()), "question_id" => $form->getId()]]
		);

		$productFieldset = $form->addFieldset('product_fieldset', []);

		$productFieldset->addField(
			'product_grid_container',
			'note',
			[
				'label' => __('Product'),
				'title' => __('Product'),
				'text' => $productBlock->toHtml(),
			]
		);

		$productFieldset->addField(
			'product_ids',
			'hidden',
			[
				'name' => 'product_ids',
				'data-form-part' => self::FORM_NAME,
				'after_element_js' => $this->getProductIdsJs($question->getProductId()),
			]
		);

		return $form;
	}

	private function getProductIdsJs($prod_ids) {
		return <<<HTML
    <script type="text/javascript">
		  require([
				'mage/adminhtml/grid'
			], function(){
				new serializerController('product_question_product_ids', [$prod_ids], [], productsGridJsObject, 'product_question_product_ids');
			});
    </script>
HTML;
	}
	/**
	 * Prepare ProductQuestion for tab
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
