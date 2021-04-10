<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\parser\visitor;

use gossi\codegen\model\PhpClass;
use gossi\codegen\parser\visitor\parts\TypeParserPart;
use PhpParser\Node\Stmt\Class_;

class ClassParserVisitor extends StructParserVisitor {
	use TypeParserPart;

	public function visitClass(Class_ $node): void {
		/** @var PhpClass $struct */
		$struct = $this->getStruct();

		if ($node->extends !== null) {
			$parentClassName = ($node->extends->getType() === 'Name_FullyQualified' ? '\\' : '') .
				implode('\\', $node->extends->parts);
			$struct->setParentClassName($parentClassName);
		}

		foreach ($node->implements as $interface) {
			$interfaceName = ($interface->getType() === 'Name_FullyQualified' ? '\\' : '') .
				implode('\\', $interface->parts);
			$struct->addInterface($interfaceName);
		}

		$struct->setAbstract($node->isAbstract());
		$struct->setFinal($node->isFinal());
	}
}
