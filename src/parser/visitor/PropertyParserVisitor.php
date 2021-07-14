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
use gossi\codegen\model\PhpProperty;
use gossi\codegen\model\PhpTrait;
use gossi\codegen\parser\visitor\parts\MemberParserPart;
use gossi\codegen\parser\visitor\parts\TypeParserPart;
use gossi\codegen\parser\visitor\parts\ValueParserPart;
use PhpParser\Node\Stmt\Property;

class PropertyParserVisitor extends StructParserVisitor {
	use ValueParserPart;
	use MemberParserPart;
	use TypeParserPart;

	public function visitProperty(Property $node): void {
		$prop = $node->props[0];

		/** @var PhpClass|PhpTrait $struct */
		$struct = $this->struct;

		$p = new PhpProperty($prop->name->name);
		$p->setStatic($node->isStatic());
		$p->setVisibility($this->getVisibility($node));
		$this->parseDocblock($p, $node->getDocComment());
		$this->parseType($p, $node, $p->getDocblock());
		$this->parseValue($p, $prop);
		$struct->setProperty($p);
	}
}
