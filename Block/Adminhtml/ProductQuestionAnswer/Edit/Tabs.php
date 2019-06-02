<?php
/**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Block\Adminhtml\ProductQuestionAnswer\Edit;

/**
 * @method Tabs setTitle(\string $title)
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs {
	/**
	 * constructor
	 *
	 * @return void
	 */
	protected function _construct() {
		parent::_construct();

		$this->setId('pdftemplate_tabs');

		$this->setDestElementId('edit_form');

		$this->setTitle(__('Product Q/A Information'));
	}
}
