<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view 
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flídr (https://github.com/mvccore/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/4.0.0/LICENCE.md
 */

namespace MvcCore\Ext\Forms\Validators;

/**
 * Responsibility: Validate raw user input. Parse float value if possible by 
 *				   `Intl` extension or try to determinate floating point 
 *				   automatically and return `float` or `NULL`.
 */
class FloatNumber extends \MvcCore\Ext\Forms\Validators\Number
{
	/**
	 * Error message index(es).
	 * @var int
	 */
	const ERROR_FLOAT = 5;

	/**
	 * Validation failure message template definitions.
	 * @var array
	 */
	protected static $errorMessages = [
		self::ERROR_FLOAT	=> "Field `{0}` requires a valid float number (from `{1}` to `{2}`).",
	];

	/**
	 * Validate raw user input. Parse float value if possible by `Intl` extension 
	 * or try to determinate floating point automatically and return `float` or `NULL`.
	 * @param string|array	$submitValue Raw user input.
	 * @return float|NULL	Safe submitted value or `NULL` if not possible to return safe value.
	 */
	public function Validate ($rawSubmittedValue) {
		$rawSubmittedValue = trim((string) $rawSubmittedValue);
		if (mb_strlen($rawSubmittedValue) > 0) {
			$result = $this->parseFloat($rawSubmittedValue);
			if ($result === NULL) {
				$min = $this->min === NULL 
					? (defined('PHP_FLOAT_MIN ') ? PHP_FLOAT_MIN : '-1.8e308')
					: (string) $this->min;
				$max = $this->max === NULL 
					? (defined('PHP_FLOAT_MAX ') ? PHP_FLOAT_MAX : '1.8e308')
					: (string) $this->max;
				$this->field->AddValidationError(
					static::GetErrorMessage(self::ERROR_FLOAT, [$min, $max])
				);
			}
		}
		return NULL;
	}
}
