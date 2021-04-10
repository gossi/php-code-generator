<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\generator\comparator;

use gossi\codegen\model\PhpConstant;
use phootwork\lang\Comparator;

/**
 * Default property comparator
 *
 * Orders them by lower cased first, then upper cased
 */
class DefaultConstantComparator implements Comparator {
	private DefaultUseStatementComparator $comparator;

	public function __construct() {
		$this->comparator = new DefaultUseStatementComparator();
	}

	/**
	 * @param mixed $a
	 * @param mixed $b
	 *
	 * @throws \TypeError
	 *
	 * @return int
	 */
	public function compare(mixed $a, mixed $b): int {
		if (!($a instanceof PhpConstant) || !($b instanceof PhpConstant)) {
			throw new \TypeError('DefaultConstantComparator::compare method compares PhpConstant objects only');
		}

		return $this->comparator->compare($a->getName(), $b->getName());
	}
}
