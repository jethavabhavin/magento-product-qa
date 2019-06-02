<?php
/**
 * @category  Bhavin Product360Image
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Block;

use Bhavin\ProductQA\Helper\Data;
use Bhavin\ProductQA\Model\ResourceModel\QuestionAnswer\CollectionFactory as AnswerCollection;
use Bhavin\ProductQA\Model\Source\ActionType;
use Bhavin\ProductQA\Model\Source\Status;
use Magento\Backend\Block\Template\Context;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Registry;

class ProductQAnswer extends \Magento\Framework\View\Element\Template {
	/**
	 * @var _default_curr_page
	 */
	protected $_default_curr_page = 1;
	/**
	 * @var _total_question
	 */
	protected $_total_question;
	/**
	 * @var int
	 */
	protected $_page_size = 0;
	/**
	 * @var _total_question
	 */
	protected $_total_answer;
	/**
	 * @var int
	 */
	protected $_question_id;
	/**
	 * @var \Bhavin\ProductQA\Helper\Data
	 */
	protected $_helper;
	/**
	 * @var \Magento\Customer\Model\Session
	 */
	protected $_customerSession;
	/**
	 * @var \Magento\Framework\Data\Form\FormKey
	 */
	protected $_formKey;
	/**
	 * @var \Bhavin\ProductQA\Model\ResourceModel\QuestionAnswer\CollectionFactory
	 */
	protected $_productQuestionAnswer = false;
	/*
		*array,
	*/
	protected $_answersCollection;
	/**
	 * Core registry
	 *
	 * @var \Magento\Framework\Registry
	 */
	protected $_coreRegistry = null;

	/**
	 * @param \Magento\Framework\View\Element\Template\Context $context
	 * @param \Magento\Framework\Registry $registry
	 * @param array $data
	 */
	public function __construct(
		Data $helper,
		AnswerCollection $productQuestionAnswer,
		Registry $coreRegistry,
		Context $context,
		CustomerSession $_customerSession,
		array $data = []
	) {
		$this->_helper = $helper;

		$this->_customerSession = $_customerSession;

		$this->_formKey = $context->getFormKey();

		$this->_coreRegistry = $coreRegistry;

		$this->_productQuestionAnswer = $productQuestionAnswer;

		parent::__construct($context, $data);

	}

	/**
	 * getTotalQuestion
	 *
	 * return int
	 */
	public function getTotalQuestion() {
		return $this->_total_answer;
	}
	/**
	 * getTotalQuestion
	 *
	 * return int
	 */
	public function getTotalPage() {
		return floor($this->getTotalQuestion() / $this->getProductQaHelper()->getQuestionPageSize());
	}
	/**
	 * canShowLoadMore
	 *
	 * return boolean
	 */
	public function canShowLoadMore() {
		return $this->_page_size != 0 && $this->_answersCollection->getSize() > 1 ? true : false;
	}
	/**
	 * getQuestionId
	 *
	 * get current question
	 *
	 * return object
	 */
	public function getQuestionId() {
		if ($this->_question_id) {
			return $this->_question_id;
		} else {
			return $this->getRequest()->getParam("question_id");
		}
	}
	/**
	 * setQuestionId
	 *
	 * set current question
	 *
	 * return this
	 */
	public function setQuestionId($question_id) {
		$this->_question_id = $question_id;

		return $this;
	}
	/**
	 * getLoginCustomer
	 *
	 * get current login customer
	 *
	 * return object
	 */
	public function getLoginCustomer() {
		return $this->_customerSession->getCustomer();
	}

	/**
	 * getEmail
	 *
	 * return string email
	 */
	public function getCustomerId() {
		return $this->getLoginCustomer()->getId();
	}
	/**
	 * getEmail
	 *
	 * return string email
	 */
	public function getCustomerEmail() {
		return $this->getLoginCustomer()->getEmail();
	}

	/**
	 * getName
	 *
	 * return string name
	 */
	public function getCustomerName() {
		return $this->getLoginCustomer()->getName();
	}
	/**
	 * isLogin
	 *
	 * return boolean
	 */
	public function isLogin() {
		if ($this->_customerSession->isLoggedIn()) {
			return true;
		}
		return false;
	}

	/**
	 * getFormUrl
	 *
	 * return string url
	 */
	public function setPageSize($page_size) {
		$this->_page_size = $page_size;

		return $this;
	}

	/**
	 * getLikeActionUrl
	 *
	 * return string url
	 */
	public function getLikeActionUrl($answer_id) {
		return $this->getUrl("productqa/question/action/action/" . ActionType::ACTION_LIKE . "/answer_id/" . $answer_id . "/");
	}
	/**
	 * getDislikeActionUrl
	 *
	 * return string url
	 */
	public function getDislikeActionUrl($answer_id) {
		return $this->getUrl("productqa/question/action/action/" . ActionType::ACTION_DISLIKE . "/answer_id/" . $answer_id . "/");
	}
	/**
	 * getLoadMoreUrl
	 *
	 * return string url
	 */
	public function getLoadMoreUrl() {
		return $this->getUrl("productqa/question/answerall/question_id/" . $this->getQuestionId());
	}
	/**
	 * getFormUrl
	 *
	 * return string url
	 */
	public function getFormUrl() {
		return $this->getUrl("productqa/question/answer/question_id/" . $this->getQuestionId());
	}
	/**
	 * getFormKey
	 *
	 * return string form key
	 */
	public function getFormKey() {
		return $this->_formKey->getFormKey();
	}

	/**
	 * getQuestions
	 *
	 * ProductAnswer of ProductQA
	 *
	 * return collection
	 */
	public function getQuestionsAnswer() {
		$answers = [];

		$question_id = $this->getQuestionId();

		if ($question_id) {
			$this->_answersCollection = $this->_productQuestionAnswer->create();

			$this->_answersCollection->addFieldToFilter('question_id', $question_id)
				->addFieldToFilter('status', Status::STATUS_APPROVE)
				->setPageSize($this->_page_size)
				->setOrder('created_at', 'DESC');

			$answers = $this->_answersCollection;
		}

		$this->_total_answer = $this->_answersCollection->getSize();

		return $answers;
	}
	/**
	 * getCurrPage
	 *
	 * return boolean
	 */
	public function getCurrPage() {
		$page = $this->getRequest()->getParam('page');

		if (!$page) {
			$page = $this->_default_curr_page;
		}

		return $page;
	}
	/**
	 * Retrieve Module Data Helper
	 *
	 * @return _helper
	 */
	public function getProductQaHelper() {
		return $this->_helper;
	}
}