<?php
 /**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Controller\Adminhtml\ProductQuestion;

use \Bhavin\ProductQA\Model\ProductQuestionFactory;
use \Magento\Framework\Registry;
use \Magento\Backend\Model\View\Result\RedirectFactory;
use \Magento\Backend\App\Action\Context;
use \Bhavin\ProductQA\Helper\Data;
use \Bhavin\ProductQA\Model\Upload;
use \Bhavin\ProductQA\Model\ResourceModel\Image; 
		
class Save extends \Bhavin\ProductQA\Controller\Adminhtml\ProductQuestion
{
	/**
     * Access Resource ID
     * 
     */
	const RESOURCE_ID = 'Bhavin_ProductQA::product_question_save';
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
     * ProductQuestion Data Helper
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
     * @param ProductQuestionFactory $product_questionFactory
     * @param Registry $registry
     * @param RedirectFactory $resultRedirectFactory
     * @param Context $context
     */
    public function __construct(
        ProductQuestionFactory $product_questionFactory,
        Registry $registry,
        Context $context,
		Data $product_questionHelper
    )
    {
        $this->_backendSession = $context->getSession();
		
		$this->_product_questionHelper = $product_questionHelper;		
		
        parent::__construct($product_questionFactory, $registry, $context);
		
    }

    /**
     * run the action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
		$product_question = $this->_initProductQuestion();
			
        $data = $this->getRequest()->getPost('product_question');
		
        $resultRedirect = $this->resultRedirectFactory->create();
		if ($data) 
		{	
			$template_data = $this->getRequest()->getPost('template');
			
			$data['template_data'] =  $this->_product_questionHelper->serializeSetting($template_data);
			
            $product_question->setData($data);
			
            $this->_eventManager->dispatch(
                'bhavin_productqa_product_question_prepare_save',
                [
                    'product_question' => $product_question,
                    'request' => $this->getRequest()
                ]
            );
			
            try 
			{
                $product_question->save();
				
                $this->messageManager->addSuccess(__('The Product Question has been saved.'));
				
                $this->_backendSession->setBhavinProductQAData(false);
				
                if ($this->getRequest()->getParam('back')) 
				{
                    $resultRedirect->setPath(
                        'bhavin_productqa/*/edit',
                        [
                            'id' => $product_question->getId(),
                            '_current' => true
                        ]
                    );
					
                    return $resultRedirect;
                }
				
                $resultRedirect->setPath('bhavin_productqa/*/');
				
                return $resultRedirect;
				
            } 
			catch (\Magento\Framework\Exception\LocalizedException $e) 
			{
                $this->messageManager->addError($e->getMessage());
            } 
			catch (\RuntimeException $e) 
			{
                $this->messageManager->addError($e->getMessage());
            } 
			catch (\Exception $e) 
			{
                $this->messageManager->addException($e, __('Something went wrong while saving the Product Question.'));
            }
			
            $this->_getSession()->setBhavinProductQAPostData($data);
			
            $resultRedirect->setPath(
                'bhavin_productqa/*/edit',
                [
                    'id' => $product_question->getId(),
                    '_current' => true
                ]
            );
			
            return $resultRedirect;
        }
		
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