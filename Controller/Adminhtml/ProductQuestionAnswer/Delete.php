<?php
 /**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Controller\Adminhtml\ProductQuestionAnswer;

class Delete extends \Bhavin\ProductQA\Controller\Adminhtml\ProductQuestionAnswer
{
	/**
     * Access Resource ID
     * 
     */
	const RESOURCE_ID = 'Bhavin_ProductQA::product_question_delete';
    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->_resultRedirectFactory->create();
		
        $id = $this->getRequest()->getParam('id');
		
        if ($id) 
		{
            $name = "";
			
            try 
			{
                /** @var \Bhavin\ProductQA\Model\ProductQuestionAnswer $product_questionanswer */
                $product_questionanswer = $this->_productQuestionAnswerFactory->create();
				
                $product_questionanswer->load($id);
				
                $name = $product_questionanswer->getName();
				
                $product_questionanswer->delete();
				
                $this->messageManager->addSuccess(__('The ProductQuestionAnswer has been deleted.'));
				
                $this->_eventManager->dispatch(
                    'adminhtml_bhavin_productqa_product_questionanswer_on_delete',
                    ['name' => $name, 'status' => 'success']
                );
				
                $resultRedirect->setPath('bhavin_productqa/*/');
				
                return $resultRedirect;
				
            } 
			catch (\Exception $e) 
			{
                $this->_eventManager->dispatch(
                    'adminhtml_bhavin_productqa_label_on_delete',
                    ['name' => $name, 'status' => 'fail']
                );
				
                // display error message
                $this->messageManager->addError($e->getMessage());
				
                // go back to edit form
                $resultRedirect->setPath('bhavin_productqa/*/edit', ['id' => $id]);
				
                return $resultRedirect;
            }
        }
		
        // display error message
        $this->messageManager->addError(__('ProductQuestionAnswer to delete was not found.'));
		
        // go to grid
        $resultRedirect->setPath('bhavin_productqa/*/');
		
        return $resultRedirect;
    }
	
	/*
	 * Check permission via ACL resource
	 */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed(Self::RESOURCE_ID);
	}
	
}
