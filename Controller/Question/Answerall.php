<?php
namespace Bhavin\ProductQA\Controller\Question;

use \Bhavin\ProductQA\Model\ProductQuestionAnswerFactory;
use \Magento\Framework\App\Action\Context;
use \Magento\Framework\View\Result\LayoutFactory;

class Answerall extends \Magento\Framework\App\Action\Action {
	/**
	 * @var \Bhavin\ProductQA\Model\ResourceModel\ProductQuestionFactory
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
		ProductQuestionAnswerFactory $productQAnswer
	) {
		$this->_productQAnswer = $productQAnswer;

		$this->_resultLayoutFactory = $resultLayoutFactory;

		parent::__construct($context);
	}

	public function execute() {
		return $this->_resultLayoutFactory->create();
	}
}