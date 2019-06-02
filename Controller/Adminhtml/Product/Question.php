<?php
/**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Controller\Adminhtml\Product;

use Bhavin\ProductQA\Model\QuestionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\Registry;

abstract class Question extends \Magento\Backend\App\Action {
	/**
	 * Post Factory
	 *
	 * @var \Bhavin\ProductQA\Model\QuestionFactory
	 */
	protected $_product_questionFactory;

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
		QuestionFactory $product_questionFactory,
		Registry $coreRegistry,
		Context $context
	) {
		$this->_product_questionFactory = $product_questionFactory;

		$this->_coreRegistry = $coreRegistry;

		$this->_resultRedirectFactory = $context->getResultRedirectFactory();

		parent::__construct($context);
	}

	/**
	 * Init Post
	 *
	 * @return \Bhavin\ProductQA\Model\Question
	 */
	protected function _initQuestion() {
		$product_questionid = (int) $this->getRequest()->getParam('id');

		/** @var \Bhavin\ProductQA\Model\Question $product_question */
		$product_question = $this->_product_questionFactory->create();

		if ($product_questionid) {
			$product_question->load($product_questionid);
		}

		$this->_coreRegistry->register('product_question', $product_question);

		return $product_question;
	}
}
