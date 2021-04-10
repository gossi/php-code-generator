<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\parser\visitor;

use gossi\codegen\model\AbstractPhpMember;
use gossi\codegen\model\AbstractPhpStruct;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpConstant;
use gossi\codegen\model\PhpTrait;
use PhpParser\Comment\Doc;
use PhpParser\Node\Const_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\Node\Stmt\UseUse;

class StructParserVisitor implements ParserVisitorInterface {
	protected AbstractPhpStruct $struct;

	/**
	 * @return AbstractPhpStruct
	 */
	protected function getStruct(): AbstractPhpStruct {
		return $this->struct;
	}

	public function __construct(AbstractPhpStruct $struct) {
		$this->struct = $struct;
	}

	public function parseDocblock(AbstractPhpStruct|AbstractPhpMember|PhpConstant $model, ?Doc $doc = null): void {
		if ($doc !== null) {
			$model->setDocblock($doc->getReformattedText());
			$docblock = $model->getDocblock();
			$model->setDescription($docblock->getShortDescription());
			$model->setLongDescription($docblock->getLongDescription());
		}
	}

	public function visitStruct(ClassLike $node): void {
		$this->struct->setName($node->name->name);
		$this->parseDocblock($this->struct, $node->getDocComment());
	}

	public function visitTraitUse(TraitUse $node): void {
		foreach ($node->traits as $trait) {
			if ($this->struct instanceof PhpClass || $this->struct instanceof PhpTrait) {
				$this->struct->addTrait(implode('\\', $trait->parts));
			}
		}
	}

	public function visitNamespace(Namespace_ $node): void {
		if ($node->name !== null) {
			$this->struct->setNamespace(implode('\\', $node->name->parts));
		}
	}

	public function visitUseStatement(UseUse $node): void {
		$this->struct->addUseStatement(implode('\\', $node->name->parts), (string) $node->alias?->name);
	}

	public function visitClass(Class_ $node): void {
	}

	public function visitInterface(Interface_ $node): void {
	}

	public function visitTrait(Trait_ $node): void {
	}

	public function visitConstants(ClassConst $node): void {
	}

	public function visitConstant(Const_ $node, Doc $doc = null): void {
	}

	public function visitProperty(Property $node): void {
	}

	public function visitMethod(ClassMethod $node): void {
	}
}
