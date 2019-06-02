<?php
/**
 * @category  Bhavin Product Question Answser
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Helper;

use Bhavin\ProductQA\Helper\Data as QaHelper;
use Bhavin\ProductQA\Model\QuestionFactory;
use Bhavin\ProductQA\Model\Source\Status;
use Magento\Catalog\Helper\ImageFactory;
use Magento\Catalog\Model\Product;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\StoreManagerInterface;

class Email {
	const MAIL_SEND_SUCCESS = 1;
	/**
	 * @var \Magento\Framework\App\Config\ScopeConfigInterfac
	 */
	protected $_transportBuilder;
	/**
	 * @var \Magento\Catalog\Model\Product
	 */
	protected $_product = false;
	/**
	 * @var \Magento\Catalog\Helper\ImageFactory
	 */
	protected $_imageHelperFactory = false;
	/**
	 * @var \Bhavin\ProductQA\Model\ResourceModel\QuestionFactory
	 */
	protected $_productQuestion = false;
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
	 *	construct
	 *
	 * @param Context $context,
	 * @param ScopeConfigInterface $scopeConfig
	 * @param Data $HelperBackend
	 */
	public function __construct(
		TransportBuilder $transportBuilder,
		Product $product,
		ImageFactory $imageHelperFactory,
		QuestionFactory $productQuestion,
		QaHelper $qaHelper,
		TimezoneInterface $timezone,
		StoreManagerInterface $storeManager
	) {
		$this->_productQuestion = $productQuestion;

		$this->_product = $product;

		$this->_imageHelperFactory = $imageHelperFactory;

		$this->_qaHelper = $qaHelper;

		$this->_timezone = $timezone;

		$this->_storeManager = $storeManager;

		$this->_transportBuilder = $transportBuilder;
	}

	/**
	 * Retrieve question per page
	 *
	 * @return boolean
	 */
	public function sendEmail($answerFaktory) {

		if (!$answerFaktory->getId()) {
			return;
		}

		$to = [];
		$data = [];

		$question_id = $answerFaktory->getQuestionId();

		$questionFaktory = $this->_productQuestion->create();

		$question = $questionFaktory->load($question_id);

		$store_id = $question->getStoreId();

		$store = $this->_storeManager->getStore($store_id);
		if (!$answerFaktory->getMailSendStatus() && $this->_qaHelper->isEmailEnable($store)) {
			if ($question->getStatus() == Status::STATUS_APPROVE) {
				$productFactory = $this->_product;
				$product = $productFactory->load($question->getProductId());

				$product_image = $this->_imageHelperFactory->create()->init($product, 'product_thumbnail_image')->getUrl();

				$answers = $answerFaktory->getCollection()->addFieldToFilter("question_id", $question_id)->addFieldToSelect("email");

				$answers->getSelect()->group("email");

				$to[] = $question->getEmail();

				foreach ($answers as $ans) {
					$to[] = $ans->getEmail();
				}

				$data = [
					'store' => $store,
					'store_id' => 1,
					"question" => $question->getQuestion(),
					"question_username" => $question->getName(),
					"question_date" => $this->_timezone->formatDate($question->getCreatedAt()),
					"new_answer" => $answerFaktory->getAnswer(),
					"new_answer_username" => $answerFaktory->getName(),
					"new_answer_date" => $this->_timezone->formatDate($answerFaktory->getCreatedAt()),
					"product_image" => $product_image,
					"product_url" => $product->getUrl(),
					"product_name" => $product->getName(),
				];

				$store_id = $data['store_id'];
				$store = $data['store'];
				$data["store_obj"] = $store;

				$store_email = $store->getConfig('trans_email/ident_general/email');

				$store_name = $store->getConfig('trans_email/ident_general/name');

				if (!empty($to)) {
					$transport = $this->_transportBuilder
						->setTemplateIdentifier('productqa_email_template')
						->setTemplateModel('Bhavin\ProductQA\Model\Email\BackendTemplate')
						->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $store_id])
						->setTemplateVars($data)
						->setFrom(['name' => $store_name, 'email' => $store_email])
						->addTo($to)
						->getTransport();

					$transport->sendMessage();

					$answerFaktory->setMailSendStatus(Self::MAIL_SEND_SUCCESS)->save();
				}
			}
		}

	}
}