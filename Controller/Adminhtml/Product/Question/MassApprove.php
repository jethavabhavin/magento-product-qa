<?php
/**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Controller\Adminhtml\Product\Question;

use Bhavin\ProductQA\Model\ResourceModel\Question\CollectionFactory;
use Bhavin\ProductQA\Model\Source\Status;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;

class MassApprove extends \Magento\Backend\App\Action {
	/**
	 * Authorization level of a basic admin session
	 *
	 * @see _isAllowed()
	 */
	const ADMIN_RESOURCE = 'Bhavin_ProductQA::product_question_massapprove';
	/**
	 * Mass Action Filter
	 *
	 * @var \Magento\Ui\Component\MassAction\Filter
	 */
	protected $_filter;

	/**
	 * Collection Factory
	 *
	 * @var \Bhavin\ProductQA\Model\ResourceModel\Question\CollectionFactory
	 */
	protected $_collectionFactory;

	/**
	 * constructor
	 *
	 * @param Filter $filter
	 * @param CollectionFactory $collectionFactory
	 * @param Context $context
	 */
	public function __construct(
		Filter $filter,
		CollectionFactory $collectionFactory,
		Context $context
	) {
		$this->_filter = $filter;

		$this->_collectionFactory = $collectionFactory;

		parent::__construct($context);
	}

	/**
	 * execute action
	 *
	 * @return \Magento\Backend\Model\View\Result\Redirect
	 */
	public function execute() {
		$collection = $this->_filter->getCollection($this->_collectionFactory->create());

		$approve = 0;

		foreach ($collection as $product_question) {
			/** @var \Bhavin\ProductQA\Model\Question $product_question */
			$product_question->setStatus(Status::STATUS_APPROVE)->save();

			$approve++;
		}

		$this->messageManager->addSuccess(__('A total of %1 record(s) have been approved.', $approve));

		/** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
		$resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);

		return $resultRedirect->setPath('*/*/');
	}
}
