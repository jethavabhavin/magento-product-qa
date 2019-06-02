<?php
/**
 * @category  Bhavin Product Question Answser
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Helper;

use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Framework\App\Helper\Context;
use \Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {
	/**
	 * @var \Magento\Framework\App\Config\ScopeConfigInterfac
	 */
	protected $_scopeConfig;
	/**
	 * @var array
	 */
	protected $_product_questionsConfig;

	/**
	 * @var \Magento\Backend\Helper\Data
	 */
	protected $_helperBackend;
	/**
	 * @var \Magento\Backend\Model\UrlInterface
	 */
	protected $_url;

	/*Extention Enable Disable Constant*/
	CONST ENABLE = 'productqa/general/enable';
	CONST CAPTCHA = 'productqa/general/captcha';
	CONST QPAGE_SIZE = 'productqa/general/qpaze_size';
	CONST ANSPAGE_SIZE = 'productqa/general/anspaze_size';
	CONST MAX_QUESTION_LENGHT = 'productqa/general/max_question_lenght';
	CONST CONF_SEND_EMAIL = 'productqa/general/sendemail';
	CONST CONF_SEND_EMAIL_TEMPLATE = 'productqa/general/email_template';
	CONST CONF_SEND_EMAIL_TEMPLATE_CSS = 'productqa/general/email_template_css';
	CONST CONF_SEND_EMAIL_SUBJECT = 'productqa/general/email_subject';
	/**
	 *	construct
	 *
	 * @param Context $context,
	 * @param ScopeConfigInterface $scopeConfig
	 * @param Data $HelperBackend
	 */
	public function __construct(
		Context $context,
		\Magento\Backend\Helper\Data $HelperBackend
	) {

		$this->_url = $context->getUrlBuilder();

		$this->_scopeConfig = $context->getScopeConfig();

		$this->_helperBackend = $HelperBackend;
	}

	/**
	 * Retrieve editor variable url
	 *
	 * @return string
	 */
	public function getEditorVariableUrl() {
		return $this->_url->getUrl('productqa/variable/template');
	}
	/**
	 * Retrieve question per page
	 *
	 * @return boolean
	 */
	public function getQuestionPageSize() {
		return $this->_scopeConfig->getValue(Self::QPAGE_SIZE, ScopeInterface::SCOPE_STORE);
	}
	/**
	 * Retrieve answer per page
	 *
	 * @return boolean
	 */
	public function getAnswerPageSize() {
		return $this->_scopeConfig->getValue(Self::ANSPAGE_SIZE, ScopeInterface::SCOPE_STORE);
	}

	/**
	 * Retrieve max character allow in question
	 *
	 * @return int
	 */
	public function getMaxQuestionLength() {
		return $this->_scopeConfig->getValue(Self::MAX_QUESTION_LENGHT, ScopeInterface::SCOPE_STORE);
	}

	/**
	 * Retrieve extention enable or disable
	 *
	 * @return boolean
	 */
	public function isExtentionEnable() {
		return $this->_scopeConfig->getValue(Self::ENABLE, ScopeInterface::SCOPE_STORE);
	}

	/**
	 * Retrieve captcha enable or disable
	 *
	 * @return boolean
	 */
	public function isCaptchaEnable() {
		return $this->_scopeConfig->getValue(Self::CAPTCHA, ScopeInterface::SCOPE_STORE);
	}
	/**
	 * Retrieve is email send enable or not
	 *
	 * @return boolean
	 */
	public function isEmailEnable($store = null) {
		if ($store) {
			$data = $store->getConfig(Self::CONF_SEND_EMAIL);
		} else {
			$data = $this->_scopeConfig->getValue(Self::CONF_SEND_EMAIL, ScopeInterface::SCOPE_STORE);
		}
		return $data;
	}
	/**
	 * Retrieve email subject
	 *
	 * @return string
	 */
	public function getEmailSubject($store = null) {
		if ($store) {
			$data = $store->getConfig(Self::CONF_SEND_EMAIL_SUBJECT);
		} else {
			$data = $this->_scopeConfig->getValue(Self::CONF_SEND_EMAIL_SUBJECT, ScopeInterface::SCOPE_STORE);
		}
		return $data;
	}
	/**
	 * Retrieve email html template
	 *
	 * @return string
	 */
	public function getEmailTemplate($store = null) {
		if ($store) {
			$data = $store->getConfig(Self::CONF_SEND_EMAIL_TEMPLATE);
		} else {
			$data = $this->_scopeConfig->getValue(Self::CONF_SEND_EMAIL_TEMPLATE, ScopeInterface::SCOPE_STORE);
		}
		return $data;
	}

	/**
	 * Retrieve email template css
	 *
	 * @return string
	 */
	public function getEmailTemplateCss($store = null) {
		$data = '';
		if ($store) {
			$data = $store->getConfig(Self::CONF_SEND_EMAIL_TEMPLATE_CSS);
		}
		return $data;
	}

	/**
	 * Retrieve serialize setting
	 *
	 * @return array
	 */
	public function serializeSetting($data) {
		return serialize($data);
	}

	/**
	 * Retrieve unserialize setting
	 *
	 * @return array
	 */
	public function unserializeSetting($string) {
		$data = [];

		if (!empty($string)) {
			$data = unserialize($string);
		}

		return $data;
	}
}