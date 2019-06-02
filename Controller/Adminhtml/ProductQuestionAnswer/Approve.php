<?php
 /**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Controller\Adminhtml\ProductQuestionAnswer;

use \Magento\Ui\Component\MassAction\Filter;
use \Bhavin\ProductQA\Model\ProductQuestionAnswerFactory;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Backend\App\Action\Context;
use Bhavin\ProductQA\Model\Source\Status;
use Magento\Framework\UrlInterface;
use \Bhavin\ProductQA\Helper\Email as QaEmailHelper;

class Approve extends \Magento\Backend\App\Action
{
	const MAIL_SEND_SUCCESS = 1;
    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_product = false;
    /**
     * @var \Magento\Catalog\Helper\ImageFactory
     */
    protected $_imageHelperFactory = false;
    /**
     * @var \Bhavin\ProductQA\Model\ResourceModel\ProductQuestionFactory
     */
    protected $_productQuestion = false;
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
     * @var \Magento\Store\Model\StoreManagerInterface
     */
	protected $_storeManager;
    /**
     * @var UrlInterface
     */
    private $_urlBuilder;
	/**
     * Access Resource ID
     * 
     */
	const RESOURCE_ID = 'Bhavin_ProductQA::productqnswer_approve';
	/**
	* Dis approve url
	*/
	const URL_ANSWER_DISAPPROVE = 'bhavin_productqa/product_questionanswer/disapprove';
    /**
     * Mass Action Filter
     * 
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $_filter;
    /**
     * Question Answer Factory
     * 
     * @var \Bhavin\ProductQA\Model\QuestionAnswerAnswerFactory
     */
    protected $_productQuestionAnswerFactory;
    /**
     * constructor
     * 
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param Context $context
     */
    public function __construct(
        Filter $filter,
        ProductQuestionAnswerFactory $productQuestionAnswerFactory,
		JsonFactory $jsonFactory,
		QaEmailHelper $qaEmailHelper,
        Context $context
    )
    {
        $this->_filter            = $filter;
		
        $this->_productQuestionAnswerFactory = $productQuestionAnswerFactory;
		
	    $this->_jsonFactory = $jsonFactory;
		
		$this->_qaEmailHelper = $qaEmailHelper;
		
        parent::__construct($context);
    }

	/*
	 * Check permission via ACL resource
	 */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed(Self::RESOURCE_ID);
	}
	
    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $answerFaktory = $this->_productQuestionAnswerFactory->create();
		
		$id = $this->getRequest()->getParam("id");
		
		$jsonResultFactory = $this->_jsonFactory->create();
		
		$status = 0;
		
		$message = "Error! while  changeing status.";
		
		if($id)
		{
			$answerFaktory->load($id);
			
			$answerFaktory->setStatus(Status::STATUS_APPROVE);
			
			$answerFaktory->save();
			
			$this->_qaEmailHelper->sendEmail($answerFaktory);
			
			$status = 1;
			
			$message = "Answer Approve successfully.";
		}
		
		$jsonResultFactory = $this->_jsonFactory->create();
		
		 $resultData = [
				'status' => $status,
				'status_text' => __("Disapprove Now"),				
				'prev_status_text' => __("Approved"),
				'message' => $message ,
				'url'=> $this->getUrl(static::URL_ANSWER_DISAPPROVE, ['id' => $id ] )
		];

		return $jsonResultFactory->setData($resultData);
    }
}
