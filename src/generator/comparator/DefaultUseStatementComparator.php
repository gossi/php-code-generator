<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\generator\comparator;

use phootwork\lang\Comparator;

/**
 * Default use statement comparator
 *
 * Compares use statements case-sensitive, with lower-case beeing sorted first
 */
class DefaultUseStatementComparator implements Comparator {
	public function compare(mixed $a, mixed $b): int {
		$a = (string) $a;
		$b = (string) $b;

		// find first difference
		$cmp1 = null;
		$cmp2 = null;
		$min = min(strlen($a), strlen($b));
		for ($i = 0; $i < $min; $i++) {
			if ($a[$i] != $b[$i]) {
				$cmp1 = $a[$i];
				$cmp2 = $b[$i];
				break;
			}
		}

		if ($cmp1 === null && $cmp2 === null) {
			return 0;
		}

		return $this->getAscii($cmp1) - $this->getAscii($cmp2);
	}

	private function getAscii(string $str): int {
		$ord = ord($str);
		if ($ord >= 65 && $ord <= 90) {
			$ord += 32;
		} elseif ($ord >= 97 && $ord <= 122) {
			$ord -= 32;
		}

		return $ord;
	}
}
