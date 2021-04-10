<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\parser\visitor\parts;

use gossi\codegen\model\AbstractPhpMember;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;

trait MemberParserPart {

	/**
	 * Returns the visibility from a node
	 *
	 * @param ClassMethod|ClassConst|Property $node
	 *
	 * @return string
	 */
	private function getVisibility(ClassMethod|ClassConst|Property $node): string {
		return match (true) {
			$node->isPrivate() => AbstractPhpMember::VISIBILITY_PRIVATE,
			$node->isProtected() => AbstractPhpMember::VISIBILITY_PROTECTED,
			default => AbstractPhpMember::VISIBILITY_PUBLIC
		};
	}
}
