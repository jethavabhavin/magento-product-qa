<?php
/**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Controller\Adminhtml\Product\Question;

class Delete extends \Bhavin\ProductQA\Controller\Adminhtml\Product\Question {
	/**
	 * Authorization level of a basic admin session
	 *
	 * @see _isAllowed()
	 */
	const ADMIN_RESOURCE = 'Bhavin_ProductQA::product_question_delete';
	/**
	 * execute action
	 *
	 * @return \Magento\Backend\Model\View\Result\Redirect
	 */
	public function execute() {
		$resultRedirect = $this->_resultRedirectFactory->create();

		$id = $this->getRequest()->getParam('id');

		if ($id) {
			$name = "";

			try
			{
				/** @var \Bhavin\ProductQA\Model\Question $product_question */
				$product_question = $this->_product_questionFactory->create();

				$product_question->load($id);

				$name = $product_question->getName();

				$product_question->delete();

				$this->messageManager->addSuccess(__('The Question has been deleted.'));

				$this->_eventManager->dispatch(
					'adminhtml_bhavin_productqa_product_question_on_delete',
					['name' => $name, 'status' => 'success']
				);

				$resultRedirect->setPath('productqa/*/');

				return $resultRedirect;

			} catch (\Exception $e) {
				$this->_eventManager->dispatch(
					'adminhtml_bhavin_productqa_label_on_delete',
					['name' => $name, 'status' => 'fail']
				);

				// display error message
				$this->messageManager->addError($e->getMessage());

				// go back to edit form
				$resultRedirect->setPath('productqa/*/edit', ['id' => $id]);

				return $resultRedirect;
			}
		}

		// display error message
		$this->messageManager->addError(__('Question to delete was not found.'));

		// go to grid
		$resultRedirect->setPath('productqa/*/');

		return $resultRedirect;
	}

	/**
	 * Check permission via ACL resource
	 */
	protected function _isAllowed() {
		return $this->_authorization->isAllowed(Self::RESOURCE_ID);
	}

}
