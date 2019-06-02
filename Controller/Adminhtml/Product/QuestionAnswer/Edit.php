<?php
/**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Controller\Adminhtml\Product\QuestionAnswer;

use Bhavin\ProductQA\Model\QuestionAnswerFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\Session;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Edit extends \Bhavin\ProductQA\Controller\Adminhtml\Product\QuestionAnswer {
	/**
	 * Authorization level of a basic admin session
	 *
	 * @see _isAllowed()
	 */
	const ADMIN_RESOURCE = 'Bhavin_ProductQA::productanswer_new';
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
	 * @param QuestionAnswerFactory $product_questionanswerFactory
	 * @param Registry $registry
	 * @param RedirectFactory $resultRedirectFactory
	 * @param Context $context
	 */
	public function __construct(
		PageFactory $resultPageFactory,
		JsonFactory $resultJsonFactory,
		QuestionAnswerFactory $product_questionanswerFactory,
		Registry $registry,
		Context $context
	) {
		$this->_backendSession = $context->getSession();

		$this->_resultPageFactory = $resultPageFactory;

		$this->_resultJsonFactory = $resultJsonFactory;

		parent::__construct($product_questionanswerFactory, $registry, $context);
	}

	/**
	 * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\View\Result\Page
	 */
	public function execute() {
		$id = $this->getRequest()->getParam('id');

		/** @var \Bhavin\ProductQA\Model\QuestionAnswer $product_questionanswer */
		$product_questionanswer = $this->_initQuestionAnswer();

		/** @var \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page $resultPage */
		$resultPage = $this->_resultPageFactory->create();

		$resultPage->setActiveMenu('Bhavin_ProductQA::product_questionanswer');

		$resultPage->getConfig()->getTitle()->set(__(' Product Question'));

		if ($id) {
			$product_questionanswer->load($id);

			if (!$product_questionanswer->getId()) {
				$this->messageManager->addError(__('This QuestionAnswer no longer exists.'));

				$resultRedirect = $this->_resultRedirectFactory->create();

				$resultRedirect->setPath(
					'productqa/*/edit',
					[
						'id' => $product_questionanswer->getId(),
						'_current' => true,
					]
				);

				return $resultRedirect;
			}
		}

		$title = $product_questionanswer->getId() ? $product_questionanswer->getName() : __('New Product Question');

		$resultPage->getConfig()->getTitle()->prepend($title);

		$data = $this->_backendSession->getData('bhavin_productqa_product_questionanswer_data', true);

		if (!empty($data)) {
			$product_questionanswer->setData($data);
		}

		return $resultPage;
	}
}
