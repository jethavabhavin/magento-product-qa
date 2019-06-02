<?php
namespace Bhavin\ProductQA\Controller\Adminhtml\Product\QuestionAnswer;

use \Bhavin\ProductQA\Model\QuestionAnswerFactory;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\View\Result\LayoutFactory;

class Answer extends \Magento\Backend\App\Action {
	/**
	 * @var \Bhavin\ProductQA\Model\ResourceModel\QuestionFactory
	 */
	protected $_productQAnswer = false;
	/**
	 * @var _storeManager
	 */
	protected $_storeManager;
	/**
	 * @var \Magento\Customer\Model\Session
	 */
	protected $_customerSession;

	protected $_resultJsonFactory = false;

	public function __construct(
		Context $context,
		LayoutFactory $resultLayoutFactory,
		QuestionAnswerFactory $productQAnswer
	) {
		$this->_productQAnswer = $productQAnswer;

		$this->_resultLayoutFactory = $resultLayoutFactory;

		parent::__construct($context);
	}

	public function execute() {
		return $this->_resultLayoutFactory->create();
	}
}