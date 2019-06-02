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
class ActionType implements \Magento\Framework\Data\OptionSourceInterface {
	/**
	 * Status values
	 */
	const ACTION_LIKE = 1;
	const ACTION_DISLIKE = 2;

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
			['value' => self::ACTION_LIKE, 'label' => __('Like')],
			['value' => self::ACTION_DISLIKE, 'label' => __('Dislike')],
		];
	}
}
