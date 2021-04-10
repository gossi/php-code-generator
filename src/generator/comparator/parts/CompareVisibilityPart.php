<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\generator\comparator\parts;

use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpProperty;

trait CompareVisibilityPart {
	protected function compareVisibilityName(PhpProperty|PhpMethod $a, PhpProperty|PhpMethod $b): int {
		if (($aV = $a->getVisibility()) !== $bV = $b->getVisibility()) {
			$aV = 'public' === $aV ? 3 : ('protected' === $aV ? 2 : 1);
			$bV = 'public' === $bV ? 3 : ('protected' === $bV ? 2 : 1);

			return $aV > $bV ? -1 : 1;
		}

		return strcasecmp($a->getName(), $b->getName());
	}
}
