<?php
/**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Controller\Adminhtml\ProductQuestion;

use \Magento\Backend\App\Action\Context;
use \Magento\Backend\Model\View\Result\ForwardFactory;

class NewAction extends \Magento\Backend\App\Action {
	/**
	 * Authorization level of a basic admin session
	 *
	 * @see _isAllowed()
	 */
	const ADMIN_RESOURCE = 'Bhavin_ProductQA::product_question_new_edit';
	/**
	 * Redirect result factory
	 *
	 * @var \Magento\Backend\Model\View\Result\ForwardFactory
	 */
	protected $_resultForwardFactory;

	/**
	 * constructor
	 *
	 * @param ForwardFactory $resultForwardFactory
	 * @param Context $context
	 */
	public function __construct(
		ForwardFactory $resultForwardFactory,
		Context $context
	) {
		$this->_resultForwardFactory = $resultForwardFactory;

		parent::__construct($context);
	}
	/**
	 * forward to edit
	 *
	 * @return \Magento\Backend\Model\View\Result\Forward
	 */
	public function execute() {
		$resultForward = $this->_resultForwardFactory->create();

		$resultForward->forward('edit');

		return $resultForward;
	}

}