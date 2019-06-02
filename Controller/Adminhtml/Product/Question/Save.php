<?php
/**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Controller\Adminhtml\Product\Question;

use \Bhavin\ProductQA\Helper\Data;
use \Bhavin\ProductQA\Model\QuestionFactory;
use \Bhavin\ProductQA\Model\ResourceModel\Image;
use \Bhavin\ProductQA\Model\Upload;
use \Magento\Backend\App\Action\Context;
use \Magento\Backend\Model\View\Result\RedirectFactory;
use \Magento\Framework\Registry;

class Save extends \Bhavin\ProductQA\Controller\Adminhtml\Product\Question {
	/**
	 * Authorization level of a basic admin session
	 *
	 * @see _isAllowed()
	 */
	const ADMIN_RESOURCE = 'Bhavin_ProductQA::product_question_save';
	/**
	 * Upload model
	 *
	 * @var \Bhavin\ProductQA\Model\Upload
	 */
	protected $_uploadModel;

	/**
	 * Image model
	 *
	 * @var \Bhavin\ProductQA\Model\ResourceModel\Image
	 */
	protected $_imageModel;

	/**
	 * Backend session
	 *
	 * @var \Magento\Backend\Model\Session
	 */
	protected $_backendSession;

	/**
	 * Question Data Helper
	 *
	 * @var \Bhavin\ProductQA\Helper\Data
	 */
	protected $_product_questionHelper;

	/**
	 * constructor
	 *
	 * @param Upload $uploadModel
	 * @param File $fileModel
	 * @param Image $imageModel
	 * @param Session $backendSession
	 * @param QuestionFactory $product_questionFactory
	 * @param Registry $registry
	 * @param RedirectFactory $resultRedirectFactory
	 * @param Context $context
	 */
	public function __construct(
		QuestionFactory $product_questionFactory,
		Registry $registry,
		Context $context,
		Data $product_questionHelper
	) {
		$this->_backendSession = $context->getSession();

		$this->_product_questionHelper = $product_questionHelper;

		parent::__construct($product_questionFactory, $registry, $context);

	}

	/**
	 * run the action
	 *
	 * @return \Magento\Backend\Model\View\Result\Redirect
	 */
	public function execute() {
		$product_question = $this->_initQuestion();

		$data = $this->getRequest()->getPost('product_question');

		$resultRedirect = $this->resultRedirectFactory->create();
		if ($data) {
			$template_data = $this->getRequest()->getPost('template');

			$data['template_data'] = $this->_product_questionHelper->serializeSetting($template_data);

			$product_question->setData($data);

			$this->_eventManager->dispatch(
				'bhavin_productqa_product_question_prepare_save',
				[
					'product_question' => $product_question,
					'request' => $this->getRequest(),
				]
			);

			try
			{
				$product_question->save();

				$this->messageManager->addSuccess(__('The Product Question has been saved.'));

				$this->_backendSession->setBhavinProductQAData(false);

				if ($this->getRequest()->getParam('back')) {
					$resultRedirect->setPath(
						'productqa/*/edit',
						[
							'id' => $product_question->getId(),
							'_current' => true,
						]
					);

					return $resultRedirect;
				}

				$resultRedirect->setPath('productqa/*/');

				return $resultRedirect;

			} catch (\Magento\Framework\Exception\LocalizedException $e) {
				$this->messageManager->addError($e->getMessage());
			} catch (\RuntimeException $e) {
				$this->messageManager->addError($e->getMessage());
			} catch (\Exception $e) {
				$this->messageManager->addException($e, __('Something went wrong while saving the Product Question.'));
			}

			$this->_getSession()->setBhavinProductQAPostData($data);

			$resultRedirect->setPath(
				'productqa/*/edit',
				[
					'id' => $product_question->getId(),
					'_current' => true,
				]
			);

			return $resultRedirect;
		}

		$resultRedirect->setPath('productqa/*/');

		return $resultRedirect;
	}
}
