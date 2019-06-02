<?php
/**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Controller\Adminhtml\Product\Question;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action {
	/**
	 * Authorization level of a basic admin session
	 *
	 * @see _isAllowed()
	 */
	const ADMIN_RESOURCE = 'Bhavin_ProductQA::product_question_grid';
	/**
	 * Result Page
	 *
	 */
	protected $_resultPage = null;

	/**
	 * Page factory
	 *
	 * @var \Magento\Framework\View\Result\PageFactory
	 */
	protected $_resultPageFactory = null;

	public function __construct(
		Context $context,
		PageFactory $resultPageFactory
	) {
		parent::__construct($context);

		$this->_resultPageFactory = $resultPageFactory;
	}

	public function execute() {
		//Call page factory to render layout and page content
		$this->_setPageData();

		return $this->getResultPage();
	}

	/**
	 * return  result page
	 */
	public function getResultPage() {
		if (is_null($this->_resultPage)) {
			$this->_resultPage = $this->_resultPageFactory->create();
		}

		return $this->_resultPage;
	}

	/**
	 * set page data and active menu
	 *
	 * return $this
	 */
	protected function _setPageData() {
		$resultPage = $this->getResultPage();

		$resultPage->setActiveMenu('Bhavin_ProductQA::product_question');

		$resultPage->getConfig()->getTitle()->prepend((__('Product Question')));

		//Add bread crumb
		$resultPage->addBreadcrumb(__('Bhavin'), __('Bhavin'));

		$resultPage->addBreadcrumb(__(' Product Q/A'), __('Manage Product Question'));

		return $this;
	}

}