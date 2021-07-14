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
use gossi\codegen\model\PhpTrait;
use PhpParser\Node\Stmt\Interface_;

class InterfaceParserVisitor extends StructParserVisitor {
	public function visitInterface(Interface_ $node): void {
		/** @var PhpClass|PhpTrait $struct */
		$struct = $this->struct;
		foreach ($node->extends as $name) {
			$struct->addInterface(implode('\\', $name->parts));
		}
	}
}
