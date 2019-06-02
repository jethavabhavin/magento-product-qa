<?php
 /**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Controller\Adminhtml;

use \Bhavin\ProductQA\Model\ProductQuestionAnswerFactory;
use \Magento\Framework\Registry;
use \Magento\Backend\Model\View\Result\RedirectFactory;
use \Magento\Backend\App\Action\Context;
		
abstract class ProductQuestionAnswer extends \Magento\Backend\App\Action
{
    /**
     * Post Factory
     * 
     * @var \Bhavin\ProductQA\Model\ProductQuestionFactory
     */
    protected $_productQuestionAnswerFactory;

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
        ProductQuestionAnswerFactory $productQuestionAnswerFactory,
        Registry $coreRegistry,
        Context $context
    )
    {
        $this->_productQuestionAnswerFactory           = $productQuestionAnswerFactory;
		
        $this->_coreRegistry          = $coreRegistry;
		
        $this->_resultRedirectFactory = $context->getResultRedirectFactory();
		
        parent::__construct($context);
    }

    /**
     * Init Post
     *
     * @return \Bhavin\ProductQA\Model\ProductQuestion
     */
    protected function _initProductQuestionAnswer()
    {
        $answerid  = (int) $this->getRequest()->getParam('id');
		
        /** @var \Bhavin\ProductQA\Model\ProductQuestion $product_questionanswer*/
        $product_questionanswer   = $this->_productQuestionAnswerFactory->create();
		
        if ($answerid) 
		{
            $product_questionanswer->load($answerid);
        }
		
        $this->_coreRegistry->register('bhavin_product_questionanswer', $product_questionanswer);
		
        return $product_questionanswer;
    }
}
