<?php
namespace Bhavin\ProductQA\Controller\Question;
 
use \Magento\Framework\App\Action\Context;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Bhavin\ProductQA\Model\ProductQuestionAnswerFactory;;
use \Bhavin\ProductQA\Model\ProductQuestionFactory;
use \Magento\Catalog\Model\Product;
use \Magento\Catalog\Helper\ImageFactory;
use \Bhavin\ProductQA\Helper\Email as QaEmailHelper;
use \Bhavin\ProductQA\Helper\Data as QaHelper ;
use \Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use \Magento\Store\Model\StoreManagerInterface;
use \Bhavin\ProductQA\Model\Source\UserType;
use \Bhavin\ProductQA\Model\Source\Status;
use \Magento\Customer\Model\Session as CustomerSession;
 
class Answer extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Bhavin\ProductQA\Helper\Email
     */
    protected $_qaEmailHelper;	
    /**
     * @var \Bhavin\ProductQA\Helper\Data
     */
    protected $_qaHelper;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_timezone;
    /**
     * @var \Bhavin\ProductQA\Model\ResourceModel\ProductQuestionFactory
     */
    protected $_productQAnswer = false;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    /**
     * @var \Bhavin\ProductQA\Model\Source\Status;
     */
    protected $_status;
	
    protected $_resultJsonFactory = false;
	
    public function __construct(
		Context $context, 
		JsonFactory $resultJsonFactory,
		CustomerSession $_customerSession,
		ProductQuestionAnswerFactory $productQAnswer,
		QaEmailHelper $qaEmailHelper,
		QaHelper $qaHelper,
		TimezoneInterface $timezone,		
		Status $status
		)
    {
        $this->_productQAnswer = $productQAnswer;
		
		$this->_qaHelper = $qaHelper;

		$this->_qaEmailHelper = $qaEmailHelper;

		$this->_timezone = $timezone;
		
		$this->_customerSession = $_customerSession;
		
		$this->_resultJsonFactory = $resultJsonFactory;
		
		$this->_status = $status->getOptionArray();
				 
        parent::__construct($context);
    }
 
    public function execute()
    {
		$name = $this->getRequest()->getPost("name");
		 
		$email = $this->getRequest()->getPost("email");
		 
		 $answer = $this->getRequest()->getPost("answer");
		 
		 $answerdata =[];
		 
		 if($answer && $email && $name)
		 {
			 $question_id = $this->getRequest()->getParam("question_id");
			 
			 $status = $this->getRequest()->getParam("adminstatus");
			 
			 $user_id = $this->_customerSession->getCustomer()->getId();
			 
			 $answer_by = $user_id;
			 
			 $user_type = UserType::CUSTOMER;
			 
			 if(! $status )
			 {
				$status =  Status::STATUS_PANDING;
			 }
			 
			 $answerFaktory =  $this->_productQAnswer->create();
			 
			 $answerFaktory->setQuestionId($question_id)
				->setUserId($user_id)
				->setAnswerBy($user_id)
				->setUserType($user_type)
				->setStatus($status)
				->setName($name)
				->setEmail($email)
				->setAnswer($answer)
				->save();
				
				if($answerFaktory->getId())
				{
					$message = __('Your Answer submited successfull. Please wait until admin approve your answer.');
					
					$this->_qaEmailHelper->sendEmail($answerFaktory);
					
					$status = 1;
				}
				else
				{
					 $message = __('Error! while saving data. Please try latter.');
					$status = 0;
				}
				
				$answerdata = [
					'id'=>$answerFaktory->getId(),
					'answer'=>$answerFaktory->getAnswer(),
					'name'=>$answerFaktory->getName(),
					'date'=>$this->_timezone->formatDate($answerFaktory->getCreatedAt()),
					'status'=>__($this->_status[$answerFaktory->getStatus()])
					];
		 }	 
		 else
		 {
			 $message = __('Error! All fields are required.');
			 $status = 0;
		 }
		 
		 $result = $this->_resultJsonFactory->create();
		 
		 $resultData = [
				'status' => $status,
				'message' => $message ,
				'answer'=>$answerdata
			];

		return $result->setData($resultData);
		 
    }
}