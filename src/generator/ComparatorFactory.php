<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\generator;

use gossi\codegen\generator\comparator\DefaultConstantComparator;
use gossi\codegen\generator\comparator\DefaultMethodComparator;
use gossi\codegen\generator\comparator\DefaultPropertyComparator;
use gossi\codegen\generator\comparator\DefaultUseStatementComparator;
use phootwork\lang\Comparator;

class ComparatorFactory {

	/**
	 * Creates a comparator for use statements
	 *
	 * @param string $type
	 *
	 * @return Comparator
	 */
	public static function createUseStatementComparator(string $type): Comparator {
		// 		switch ($type) {
		// 			case CodeGenerator::SORT_USESTATEMENTS_DEFAULT:
		// 			default:
		// 				return new DefaultUseStatementComparator();
		// 		}
		return new DefaultUseStatementComparator();
	}

	/**
	 * Creates a comparator for constants
	 *
	 * @param string $type
	 *
	 * @return Comparator
	 */
	public static function createConstantComparator(string $type): Comparator {
		// 		switch ($type) {
		// 			case CodeGenerator::SORT_CONSTANTS_DEFAULT:
		// 			default:
		// 				return new DefaultConstantComparator();
		// 		}
		return new DefaultConstantComparator();
	}

	/**
	 * Creates a comparator for properties
	 *
	 * @param string $type
	 *
	 * @return Comparator
	 */
	public static function createPropertyComparator(string $type): Comparator {
		// 		switch ($type) {
		// 			case CodeGenerator::SORT_PROPERTIES_DEFAULT:
		// 			default:
		// 				return new DefaultPropertyComparator();
		// 		}
		return new DefaultPropertyComparator();
	}

	/**
	 * Creates a comparator for methods
	 *
	 * @param string $type
	 *
	 * @return Comparator
	 */
	public static function createMethodComparator(string $type): Comparator {
		// 		switch ($type) {
		// 			case CodeGenerator::SORT_METHODS_DEFAULT:
		// 			default:
		// 				return new DefaultMethodComparator();
		// 		}
		return new DefaultMethodComparator();
	}
}
