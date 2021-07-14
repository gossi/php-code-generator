<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\parser\visitor;

use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use gossi\codegen\parser\PrettyPrinter;
use gossi\codegen\parser\visitor\parts\MemberParserPart;
use gossi\codegen\parser\visitor\parts\TypeParserPart;
use gossi\codegen\parser\visitor\parts\ValueParserPart;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;

class MethodParserVisitor extends StructParserVisitor {
	use MemberParserPart;
	use ValueParserPart;
	use TypeParserPart;

	public function visitMethod(ClassMethod $node): void {
		$method = new PhpMethod($node->name->name);
		$method->setAbstract($node->isAbstract());
		$method->setFinal($node->isFinal());
		$method->setVisibility($this->getVisibility($node));
		$method->setStatic($node->isStatic());
		$method->setReferenceReturned($node->returnsByRef());

		$this->parseDocblock($method, $node->getDocComment());

		$this->parseType($method, $node, $method->getDocblock());
		$this->parseParams($method, $node);
		$this->parseBody($method, $node);

		$this->struct->setMethod($method);
	}

	private function parseParams(PhpMethod $method, ClassMethod $node): void {
		foreach ($node->params as $param) {
			$name = $param->var instanceof Variable ? $param->var->name : '';

			$p = new PhpParameter();
			$p->setName($name);
			$p->setPassedByReference($param->byRef);

			$this->parseType($p, $param, $method->getDocblock());
			$this->parseValue($p, $param);

			$method->addParameter($p);
		}
	}

	private function parseBody(PhpMethod $method, ClassMethod $node): void {
		$stmts = $node->getStmts();
		if (is_array($stmts) && count($stmts)) {
			$prettyPrinter = new PrettyPrinter();
			$method->setBody($prettyPrinter->prettyPrint($stmts));
		}
	}
}
