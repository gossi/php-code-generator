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
use gossi\codegen\model\PhpMethod;
use phootwork\lang\Comparator;

/**
 * Default property comparator
 *
 * Orders them by static first, then visibility and last by property name
 */
class DefaultMethodComparator implements Comparator {
	use CompareVisibilityPart;

	/**
	 * @param mixed $a
	 * @param mixed $b
	 *
	 * @return int
	 */
	public function compare(mixed $a, mixed $b): int {
		if (!($a instanceof PhpMethod) || !($b instanceof PhpMethod)) {
			throw new \TypeError('DefaultMethodComparator::compare method compares PhpMethod objects only');
		}

		if ($a->isStatic() !== $isStatic = $b->isStatic()) {
			return $isStatic ? 1 : -1;
		}

		return $this->compareVisibilityName($a, $b);
	}
}
