<?php
/**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\UrlInterface;
use \Bhavin\ProductQA\Model\Source\UserType;

/**
 * Class Thumbnail
 * @package Bhavin\ProductQA\Ui\Component\Listing\Columns
 */
class CustomerName extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var UrlInterface
     */
    private $_urlBuilder;
	/**
     * @var URL_CUSTOMER_EDIT
     */
    const  URL_CUSTOMER_EDIT = "customer/index/edit/";
	
    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param ImageFileUploader $imageFileUploader
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
		
        $this->_urlBuilder = $urlBuilder;
		
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) 
		{
			$fieldName = $this->getData('name');

			foreach ($dataSource['data']['items'] as & $item) 
			{
				if($item['ask_by'] && $item['user_type'] == UserType::CUSTOMER )
				{
					$customer_url = $this->_urlBuilder->getUrl(static::URL_CUSTOMER_EDIT, ['id' => $item['ask_by'] ] );
					
					$item[$this->getData('name')] = "<a href='#' onclick='setLocation(\"{$customer_url}\")'>".$item['name']."</a>";
				}
				else
				{
					$item[$this->getData('name')] = $item['name'];
				}
			}
        }
        return $dataSource;
    }
}
