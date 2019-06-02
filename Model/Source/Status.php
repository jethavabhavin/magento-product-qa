<?php
/**
 * @category  Bhavin Product Question Answser
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */

namespace Bhavin\ProductQA\Model\Source;

/**
 * Class Status
 * @package Bhavin\ProductQA\Model\Source
 */
class Status implements \Magento\Framework\Data\OptionSourceInterface {
	/**
	 * Status values
	 */
	const STATUS_APPROVE = 1;
	const STATUS_PANDING = 0;

	/**
	 * @return array
	 */
	public function getOptionArray() {
		$optionArray = ['' => ' '];

		foreach ($this->toOptionArray() as $option) {
			$optionArray[$option['value']] = $option['label'];
		}

		return $optionArray;
	}

	/**
	 * @return array
	 */
	public function toOptionArray() {
		return [
			['value' => self::STATUS_APPROVE, 'label' => __('Approved')],
			['value' => self::STATUS_PANDING, 'label' => __('Panding')],
		];
	}
}
