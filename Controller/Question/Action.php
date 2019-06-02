<?php
namespace Bhavin\ProductQA\Controller\Question;
 
use \Magento\Framework\App\Action\Context;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Bhavin\ProductQA\Model\ResourceModel\ProductqaAction\CollectionFactory as ActionCollection;
use \Bhavin\ProductQA\Model\ProductQuestionAnswerFactory;
use \Bhavin\ProductQA\Model\ProductQuestionFactory;
use \Bhavin\ProductQA\Model\Source\UserType;
use \Bhavin\ProductQA\Model\Source\Status;
use \Bhavin\ProductQA\Model\Source\ActionType;
use \Magento\Store\Model\StoreManagerInterface;
use \Bhavin\ProductQA\Helper\Data;
use \Magento\Customer\Model\Session as CustomerSession;
 
class Action extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Bhavin\ProductQA\Model\ResourceModel\ProductqaAction
     */
    protected $_roductqaActionFactory = false;
    /**
     * @var \Bhavin\ProductQA\Model\ProductQuestionAnswer
     */
    protected $_productQuestionAnswerFactory = false;
    /**
     * @var \Bhavin\ProductQA\Model\ProductQuestion
     */
    protected $_productQuestionFactory = false;
	/**
     * @var _storeManager
     */
	protected $_storeManager;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    /**
     * @var \Bhavin\ProductQA\Helper\Data
     */
    protected $_helper;
	
    protected $_resultJsonFactory = false;
	
    public function __construct(
		Data $helper,
		Context $context, 
		JsonFactory $resultJsonFactory,
		StoreManagerInterface $storeManager,
		CustomerSession $_customerSession,
		ActionCollection $actionCollectionFactory,
		ProductQuestionAnswerFactory $productQuestionAnswerFactory,
		ProductQuestionFactory $productQuestionFactory
		)
    {
		$this->_helper = $helper;
		
        $this->_actionCollectionFactory = $actionCollectionFactory;
        
		$this->_productQuestionAnswerFactory = $productQuestionAnswerFactory;
		
        $this->_productQuestionFactory = $productQuestionFactory;
		
		$this->_customerSession = $_customerSession;
		
		$this->_resultJsonFactory = $resultJsonFactory;
		
		$this->_storeManager = $storeManager;
		 
        parent::__construct($context);
    }
 
    public function execute()
    {	 		 
		$action = $this->getRequest()->getParam("action");
		
		$count  = 0;
		
		 if($this->_customerSession->isLoggedIn()) 
		 {
			 if(($action == ActionType::ACTION_LIKE || $action ==  ActionType::ACTION_DISLIKE))
			 {
				 $question = $this->getRequest()->getParam("question_id");
				 
				 $answer = $this->getRequest()->getParam("answer_id");
				
				if($question)
				{
					$object_id = $question;
					
					$object = "question";
				}
				else
				{
					$object_id = $answer;
					$object = "answer";
				}
				
				 if($object_id && $action)
				 {
					 $product_id = $this->getRequest()->getParam("product");
					 
					 $action_by = $this->_customerSession->getCustomer()->getId();
					 
					
					 $actionCollectionFactory =  $this->_actionCollectionFactory->create();
					 
					 $qaAction = $actionCollectionFactory
						->addFieldToFilter("action_by",$action_by)
						->addFieldToFilter("object_id",$object_id)
						->addFieldToFilter("action",$action)
						->addFieldToFilter("object_type",$object)
						->getFirstItem();
						
						if($object == "answer")
						{
							$qaobject = $this->_productQuestionAnswerFactory->create();
						}
						else
						{
							$qaobject = $this->_productQuestionFactory->create();
						}
					
						$qaobject->load($object_id);

						if($qaAction->getId())
						{
								if($qaAction->getAction() == $action)
								{
										if($action == ActionType::ACTION_LIKE)
										{
											$qaobject->setLikes(new \Zend_Db_Expr('likes - 1'))->save();
											
											$qaobject->load($object_id);
											
											$count  = $qaobject->getLikes();
											
										}
										else
										{
											$qaobject->setDislikes(new \Zend_Db_Expr('dislikes - 1'))->save();
											
											$qaobject->load($object_id);
											
											$count  = $qaobject->getDislikes();
										}
								
										$qaAction->delete();
								}
								else
								{
									$qaAction
										->setActionBy($action_by)
										->setObjectId($object_id)
										->setObjectType($object)
										->setAction($action)
										->save();
										
										if($action == ActionType::ACTION_LIKE)
										{
											$qaobject->setLikes(new \Zend_Db_Expr('likes + 1'))->save();
											
											$qaobject->load($object_id);
											
											$count  = $qaobject->getLikes();
										}
										else
										{
											$qaobject->setDislikes(new \Zend_Db_Expr('dislikes + 1'))->save();
											
											$qaobject->load($object_id);
											
											$count  = $qaobject->getDislikes();
											
										}
								}
						}
						else
						{
								$qaAction
									->setActionBy($action_by)
									->setObjectId($object_id)
									->setObjectType($object)
									->setAction($action)
									->save();
							
								if($action == ActionType::ACTION_LIKE)
								{
									$qaobject->setLikes(new \Zend_Db_Expr('likes + 1'))->save();
									
									$qaobject->load($object_id);
									
									$count  = $qaobject->getLikes();
								}
								else
								{
									$qaobject->setDislikes(new \Zend_Db_Expr('dislikes + 1'))->save();
									
									$qaobject->load($object_id);
									
									$count  = $qaobject->getDislikes();
								}
						}
						
						$message = __('');
						$status = 1;
						
				
				 }
				 else{
						$message = __("Error!  Please try later.");
						$status = 0;
				 }
			}
			else
			{
				 $message = __("Error!  Wrong action. Please try later.");
				 $status = 0;
			}
				
		 }	 
		 else
		 {
			 $message = __('Error! Login to do this action.');
			 $status = 0;
		 }
		 
		 $result = $this->_resultJsonFactory->create();
		 
		 $resultData = [
				'status' => $status,
				'message' => $message ,
				'count'=>$count 
			];

			return $result->setData($resultData);
		 
    }
}