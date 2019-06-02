<?php
/**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Controller\Adminhtml\ProductQuestionAnswer;

use \Bhavin\ProductQA\Helper\Data;
use \Bhavin\ProductQA\Model\ProductQuestionAnswerFactory;
use \Bhavin\ProductQA\Model\ResourceModel\Image;
use \Bhavin\ProductQA\Model\Upload;
use \Magento\Backend\App\Action\Context;
use \Magento\Backend\Model\View\Result\RedirectFactory;
use \Magento\Framework\Registry;

class Save extends \Bhavin\ProductQA\Controller\Adminhtml\ProductQuestionAnswer {
	/**
	 * Authorization level of a basic admin session
	 *
	 * @see _isAllowed()
	 */
	const ADMIN_RESOURCE = 'Bhavin_ProductQA::productanswer_save';
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
	 * ProductQuestionAnswer Data Helper
	 *
	 * @var \Bhavin\ProductQA\Helper\Data
	 */
	protected $_product_questionanswerHelper;

	/**
	 * constructor
	 *
	 * @param Upload $uploadModel
	 * @param File $fileModel
	 * @param Image $imageModel
	 * @param Session $backendSession
	 * @param ProductQuestionAnswerFactory $product_questionanswerFactory
	 * @param Registry $registry
	 * @param RedirectFactory $resultRedirectFactory
	 * @param Context $context
	 */
	public function __construct(
		ProductQuestionAnswerFactory $product_questionanswerFactory,
		Registry $registry,
		Context $context,
		Data $product_questionanswerHelper
	) {
		$this->_backendSession = $context->getSession();

		$this->_product_questionanswerHelper = $product_questionanswerHelper;

		parent::__construct($product_questionanswerFactory, $registry, $context);

	}

	/**
	 * run the action
	 *
	 * @return \Magento\Backend\Model\View\Result\Redirect
	 */
	public function execute() {
		$product_questionanswer = $this->_initProductQuestionAnswer();

		$data = $this->getRequest()->getPost('product_questionanswer');

		$resultRedirect = $this->resultRedirectFactory->create();
		if ($data) {
			$template_data = $this->getRequest()->getPost('template');

			$product_questionanswer->setData($data);

			$this->_eventManager->dispatch(
				'bhavin_productqa_product_questionanswer_prepare_save',
				[
					'product_questionanswer' => $product_questionanswer,
					'request' => $this->getRequest(),
				]
			);

			try
			{
				$product_questionanswer->save();

				$this->messageManager->addSuccess(__('The Product Answer has been saved.'));

				$this->_backendSession->setBhavinProductQAData(false);

				if ($this->getRequest()->getParam('back')) {
					$resultRedirect->setPath(
						'productqa/*/edit',
						[
							'id' => $product_questionanswer->getId(),
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
				$this->messageManager->addException($e, __('Something went wrong while saving the Product Answer.'));
			}

			$this->_getSession()->setBhavinProductQAPostData($data);

			$resultRedirect->setPath(
				'productqa/*/edit',
				[
					'id' => $product_questionanswer->getId(),
					'_current' => true,
				]
			);

			return $resultRedirect;
		}

		$resultRedirect->setPath('productqa/*/');

		return $resultRedirect;
	}
}
