<?php
/**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;

/**
 * Class Thumbnail
 * @package Bhavin\ProductQA\Ui\Component\Listing\Columns
 */
class Product extends \Magento\Ui\Component\Listing\Columns\Column {
	/**
	 * @var UrlInterface
	 */
	private $_urlBuilder;
	/**
	 * @var URL_PATH_PRODUCT_EDIT
	 */
	const URL_PATH_PRODUCT_EDIT = "catalog/product/edit/";
	/**
	 * @var UrlInterface
	 */
	private $_productCollectionFactory;

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
		ProductCollectionFactory $productCollectionFactory,
		array $components = [],
		array $data = []
	) {
		parent::__construct($context, $uiComponentFactory, $components, $data);

		$this->_urlBuilder = $urlBuilder;

		$this->_productCollectionFactory = $productCollectionFactory;

	}

	/**
	 * Prepare Data Source
	 *
	 * @param array $dataSource
	 * @return array
	 */
	public function prepareDataSource(array $dataSource) {

		if (isset($dataSource['data']['items'])) {
			$productCollection = $this->_productCollectionFactory->create();

			$prduct_ids = [];

			foreach ($dataSource['data']['items'] as $item) {
				$prduct_ids[] = $item['product_id'];
			}

			$productCollection->addFieldToSelect(['name', 'entity_id'])->addFieldToFilter('entity_id', ['in' => array_unique($prduct_ids)]);

			foreach ($productCollection as $product) {
				$prducts[$product->getId()] = $product->getName();
			}

			$fieldName = $this->getData('name');

			foreach ($dataSource['data']['items'] as &$item) {

				$prod_url = $this->_urlBuilder->getUrl(static::URL_PATH_PRODUCT_EDIT, ['id' => $item['product_id']]);

				$item[$this->getData('name')] = "<a href='#' onclick='setLocation(\"{$prod_url}\")'>" . $prducts[$item['product_id']] . "</a>";
			}
		}
		return $dataSource;
	}
}
