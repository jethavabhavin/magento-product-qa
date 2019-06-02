<?php
namespace Bhavin\ProductQA\Controller\Question;
 
use \Magento\Framework\App\Action\Context;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Bhavin\ProductQA\Model\ProductQuestionFactory;;
use \Bhavin\ProductQA\Model\Source\UserType;
use \Bhavin\ProductQA\Model\Source\Status;
use \Magento\Store\Model\StoreManagerInterface;
use \Bhavin\ProductQA\Helper\Data;
use \Magento\Customer\Model\Session as CustomerSession;
use \Magento\Captcha\Observer\CaptchaStringResolver;
use \Magento\Captcha\Helper\Data as CaptchaHelper;
 
class Ask extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Bhavin\ProductQA\Model\ResourceModel\ProductQuestionFactory
     */
    protected $_productQuestion = false;
	/**
     * @var _storeManager
     */
	protected $_storeManager;
	/**
     * @var _storeManager
     */
	CONST CAPTCHA_FORM_ID = 'qa_captcha_form_1';
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    /**
     * @var \Bhavin\ProductQA\Helper\Data
     */
    protected $_helper;
    /**
     * @var \Magento\Captcha\Observer\CaptchaStringResolver
     */
    protected $_captchaStringResolver;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;
	
    /**
     * @var \Magento\Captcha\Helper\Data
     */
    protected $_captchaHelper;
	
    protected $_resultJsonFactory = false;
	
    public function __construct(
		Data $helper,
		Context $context, 
		JsonFactory $resultJsonFactory,
		CustomerSession $_customerSession,
		ProductQuestionFactory $productQuestion,
		CaptchaStringResolver $captchaStringResolver ,
		StoreManagerInterface $storeManager,
		CaptchaHelper $captchaHelper 
		)
    {
		$this->_helper = $helper;
		
        $this->_productQuestion = $productQuestion;
		
		$this->_customerSession = $_customerSession;
		
		$this->_resultJsonFactory = $resultJsonFactory;
		
		$this->_storeManager = $storeManager;
		
		$this->_captchaStringResolver = $captchaStringResolver;
		
		$this->_captchaHelper = $captchaHelper;
		
		$this->_messageManager = $context->getMessageManager();
		
        parent::__construct($context);
    }
 
    public function execute()
    {
		 $formId = Self::CAPTCHA_FORM_ID;
		
	 	 $captchaModel = $this->_captchaHelper->getCaptcha($formId);

	  	 if ($captchaModel->isCorrect($this->_captchaStringResolver->resolve($this->getRequest(), $formId)) || !$this->_helper->isCaptchaEnable()) 
		 {
			$name = $this->getRequest()->getPost("name");
			 
			$email = $this->getRequest()->getPost("email");
			 
			 $question = $this->getRequest()->getPost("question");
			 
			 if($question && $email && $name)
			 {
				 $question_lenght = $this->_helper->getMaxQuestionLength();
				 
				 if(strlen($question) <= $question_lenght )
				 {
					 $product_id = $this->getRequest()->getParam("product");
					 
					 $user_id = $this->_customerSession->getCustomer()->getId();
					 
					 $ask_by = $user_id;
					 
					 $user_type = UserType::CUSTOMER;
					 
					 $store_id =  $this->_storeManager->getStore()->getId();
					 
					 $status =  Status::STATUS_PANDING;
					 
					 $questionFaktory =  $this->_productQuestion->create();
					 
					 $questionFaktory->setProductId($product_id)
						->setUserId($user_id)
						->setAskBy($ask_by)
						->setUserType($user_type)
						->setStoreId($store_id)
						->setStatus($status)
						->setName($name)
						->setEmail($email)
						->setQuestion($question)
						->save();
						
						if($questionFaktory->getId())
						{
							$message = __('Your Question submited successfull. Please wait until admin approve your question.');
							$status = 1;
						}
						else
						{
							 $message = __('Error! while saving data. Please try latter.');
							$status = 0;
						}
				}
				else
				{
					 $message = __('Question is too long. max %1 allow"',$question_lenght );
					$status = 0;
				}
					
			 }	 
			 else
			 {
				 $message = __('Error! All fields are required.');
				 $status = 0;
			 }
	 	 }
		 else
		 {
			 $message = __('Invalide security code!');
			 $status = 0;
		 }
		 
		 $this->messageManager->getMessages();
		 
		 $result = $this->_resultJsonFactory->create();
		 
		 $resultData = [
				'status' => $status,
				'message' => $message ,
			];

			return $result->setData($resultData);
		 
    }
}