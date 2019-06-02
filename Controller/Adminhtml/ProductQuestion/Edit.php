<?php
/**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Controller\Adminhtml\ProductQuestion;

use \Bhavin\ProductQA\Model\ProductQuestionFactory;
use \Magento\Backend\App\Action\Context;
use \Magento\Backend\Model\View\Result\RedirectFactory;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Framework\Registry;
use \Magento\Framework\View\Result\PageFactory;

class Edit extends \Bhavin\ProductQA\Controller\Adminhtml\ProductQuestion {
	/**
	 * Authorization level of a basic admin session
	 *
	 * @see _isAllowed()
	 */
	const ADMIN_RESOURCE = 'Bhavin_ProductQA::product_question_new_edit';
	/**
	 * Backend session
	 *
	 * @var \Magento\Backend\Model\Session
	 */
	protected $_backendSession;

	/**
	 * Page factory
	 *
	 * @var \Magento\Framework\View\Result\PageFactory
	 */
	protected $_resultPageFactory;

	/**
	 * Result JSON factory
	 *
	 * @var \Magento\Framework\Controller\Result\JsonFactory
	 */
	protected $_resultJsonFactory;

	/**
	 * constructor
	 *
	 * @param Session $backendSession
	 * @param PageFactory $resultPageFactory
	 * @param JsonFactory $resultJsonFactory
	 * @param ProductQuestionFactory $product_questionFactory
	 * @param Registry $registry
	 * @param RedirectFactory $resultRedirectFactory
	 * @param Context $context
	 */
	public function __construct(
		PageFactory $resultPageFactory,
		JsonFactory $resultJsonFactory,
		ProductQuestionFactory $product_questionFactory,
		Registry $registry,
		Context $context
	) {
		$this->_backendSession = $context->getSession();

		$this->_resultPageFactory = $resultPageFactory;

		parent::__construct($product_questionFactory, $registry, $context);
	}

	/**
	 * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\View\Result\Page
	 */
	public function execute() {
		$id = $this->getRequest()->getParam('id');

		/** @var \Bhavin\ProductQA\Model\ProductQuestion $product_question */
		$product_question = $this->_initProductQuestion();

		/** @var \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page $resultPage */
		$resultPage = $this->_resultPageFactory->create();

		$resultPage->setActiveMenu('Bhavin_ProductQA::product_question');

		$resultPage->getConfig()->getTitle()->set(__(' Product Question'));

		if ($id) {
			$product_question->load($id);

			if (!$product_question->getId()) {
				$this->messageManager->addError(__('This ProductQuestion no longer exists.'));

				$resultRedirect = $this->_resultRedirectFactory->create();

				$resultRedirect->setPath(
					'productqa/*/edit',
					[
						'id' => $product_question->getId(),
						'_current' => true,
					]
				);

				return $resultRedirect;
			}
		}

		$title = $product_question->getId() ? $product_question->getName() : __('New Product Question');

		$resultPage->getConfig()->getTitle()->prepend($title);

		$data = $this->_backendSession->getData('bhavin_productqa_product_question_data', true);

		if (!empty($data)) {
			$product_question->setData($data);
		}

		return $resultPage;
	}
}
