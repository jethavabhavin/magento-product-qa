<?php
/**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Controller\Adminhtml\Product;

use Bhavin\ProductQA\Model\QuestionAnswerFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\Registry;

abstract class QuestionAnswer extends \Magento\Backend\App\Action {
	/**
	 * Post Factory
	 *
	 * @var \Bhavin\ProductQA\Model\QuestionFactory
	 */
	protected $_productQuestionAnswerFactory;

	/**
	 * Core registry
	 *
	 * @var \Magento\Framework\Registry
	 */
	protected $_coreRegistry;

	/**
	 * Result redirect factory
	 *
	 * @var \Magento\Backend\Model\View\Result\RedirectFactory
	 */
	protected $_resultRedirectFactory;

	/**
	 * constructor
	 *
	 * @param QuestionFactory $product_questionFactory
	 * @param Registry $coreRegistry
	 * @param RedirectFactory $resultRedirectFactory
	 * @param Context $context
	 */
	public function __construct(
		QuestionAnswerFactory $productQuestionAnswerFactory,
		Registry $coreRegistry,
		Context $context
	) {
		$this->_productQuestionAnswerFactory = $productQuestionAnswerFactory;

		$this->_coreRegistry = $coreRegistry;

		$this->_resultRedirectFactory = $context->getResultRedirectFactory();

		parent::__construct($context);
	}

	/**
	 * Init Post
	 *
	 * @return \Bhavin\ProductQA\Model\Question
	 */
	protected function _initQuestionAnswer() {
		$answerid = (int) $this->getRequest()->getParam('id');

		/** @var \Bhavin\ProductQA\Model\Question $product_questionanswer*/
		$product_questionanswer = $this->_productQuestionAnswerFactory->create();

		if ($answerid) {
			$product_questionanswer->load($answerid);
		}

		$this->_coreRegistry->register('bhavin_product_questionanswer', $product_questionanswer);

		return $product_questionanswer;
	}
}
