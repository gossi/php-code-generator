<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\generator\comparator;

use gossi\codegen\generator\comparator\parts\CompareVisibilityPart;
use gossi\codegen\model\PhpProperty;
use phootwork\lang\Comparator;

/**
 * Default property comparator
 *
 * Orders them by visibility first then by method name
 */
class DefaultPropertyComparator implements Comparator {
	use CompareVisibilityPart;

	/**
	 * @param mixed $a
	 * @param mixed $b
	 *
	 * @return int
	 */
	public function compare(mixed $a, mixed $b): int {
		if (!($a instanceof PhpProperty) || !($b instanceof PhpProperty)) {
			throw new \TypeError('DefaultPropertyComparator::compare method compares PhpProperty objects only');
		}

		return $this->compareVisibilityName($a, $b);
	}
}
