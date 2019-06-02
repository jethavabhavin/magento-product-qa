<?php
 /**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Controller\Adminhtml;

use \Bhavin\ProductQA\Model\ProductQuestionFactory;
use \Magento\Framework\Registry;
use \Magento\Backend\Model\View\Result\RedirectFactory;
use \Magento\Backend\App\Action\Context;
		
abstract class ProductQuestion extends \Magento\Backend\App\Action
{
    /**
     * Post Factory
     * 
     * @var \Bhavin\ProductQA\Model\ProductQuestionFactory
     */
    protected $_product_questionFactory;

    /**
     * Core registry
     * 
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Result redirect factory
     * 
     * @var \Magento\Backend\Model\View\Result\RedirectFactory
     */
    protected $_resultRedirectFactory;

    /**
     * constructor
     * 
     * @param ProductQuestionFactory $product_questionFactory
     * @param Registry $coreRegistry
     * @param RedirectFactory $resultRedirectFactory
     * @param Context $context
     */
    public function __construct(
        ProductQuestionFactory $product_questionFactory,
        Registry $coreRegistry,
        Context $context
    )
    {
        $this->_product_questionFactory           = $product_questionFactory;
		
        $this->_coreRegistry          = $coreRegistry;
		
        $this->_resultRedirectFactory = $context->getResultRedirectFactory();
		
        parent::__construct($context);
    }

    /**
     * Init Post
     *
     * @return \Bhavin\ProductQA\Model\ProductQuestion
     */
    protected function _initProductQuestion()
    {
        $product_questionid  = (int) $this->getRequest()->getParam('id');
		
        /** @var \Bhavin\ProductQA\Model\ProductQuestion $product_question */
        $product_question    = $this->_product_questionFactory->create();
		
        if ($product_questionid) 
		{
            $product_question->load($product_questionid);
        }
		
        $this->_coreRegistry->register('bhavin_product_question', $product_question);
		
        return $product_question;
    }
}
