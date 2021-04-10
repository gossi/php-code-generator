<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\parser\visitor;

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

interface ParserVisitorInterface {

	/**
	 * Visit a struct
	 *
	 * @param ClassLike $node
	 *
	 * @return void
	 */
	public function visitStruct(ClassLike $node): void;

	/**
	 * Visit a class
	 *
	 * @param Class_ $node
	 *
	 * @return void
	 */
	public function visitClass(Class_ $node): void;

	/**
	 * Visit an interface
	 *
	 * @param Interface_ $node
	 *
	 * @return void
	 */
	public function visitInterface(Interface_ $node): void;

	/**
	 * Visit a trait
	 *
	 * @param Trait_ $node
	 *
	 * @return void
	 */
	public function visitTrait(Trait_ $node): void;

	/**
	 * Visit a use statement inside a struct
	 *
	 * @param TraitUse $node
	 *
	 * @return void
	 */
	public function visitTraitUse(TraitUse $node): void;

	/**
	 * Visit class constants
	 *
	 * @param ClassConst $node
	 *
	 * @return void
	 */
	public function visitConstants(ClassConst $node): void;

	/**
	 * Visit a constant
	 *
	 * @param Const_ $node
	 * @param Doc|null $doc
	 *
	 * @return void
	 */
	public function visitConstant(Const_ $node, ?Doc $doc = null): void;

	/**
	 * Visit a property
	 *
	 * @param Property $node
	 *
	 * @return void
	 */
	public function visitProperty(Property $node): void;

	/**
	 * Visit a namespace statement
	 *
	 * @param Namespace_ $node
	 *
	 * @return void
	 */
	public function visitNamespace(Namespace_ $node): void;

	/**
	 * Visit a use statement
	 *
	 * @param UseUse $node
	 *
	 * @return void
	 */
	public function visitUseStatement(UseUse $node): void;

	/**
	 * Visit a method
	 *
	 * @param ClassMethod $node
	 *
	 * @return void
	 */
	public function visitMethod(ClassMethod $node): void;
}
