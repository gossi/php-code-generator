<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\parser\visitor\parts;

use gossi\codegen\model\ValueInterface;
use gossi\codegen\parser\PrettyPrinter;
use PhpParser\Node;
use PhpParser\Node\Const_;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\MagicConst;
use PhpParser\Node\Scalar\String_;

trait ValueParserPart {

	/**
	 * @var bool[]
	 */
	private array $constMap = [
		'false' => false,
		'true' => true
	];

	/**
	 * Parses the value of a node into the model
	 * 
	 * @param ValueInterface $obj
	 * @param Node $node
	 *
	 * @return void
	 */
	private function parseValue(ValueInterface $obj, Node $node): void {
		$value = $node instanceof Const_ ? $node->value : $node->default;
		if ($value !== null) {
			$this->isPrimitive($value) ? $obj->setValue($this->getPrimitiveValue($value)) :
				$obj->setExpression($this->getExpression($value));
		}
	}

	/**
	 * Returns whether this node is a primitive value
	 * 
	 * @param Node $node
	 *
	 * @return bool
	 */
	private function isPrimitive(Node $node): bool {
		return $node instanceof String_
			|| $node instanceof LNumber
			|| $node instanceof DNumber
			|| $this->isBool($node)
			|| $this->isNull($node);
	}

	/**
	 * Returns the primitive value
	 *
	 * @param Node $node
	 *
	 * @return mixed
	 */
	private function getPrimitiveValue(Node $node): mixed {
		if ($this->isBool($node)) {
			return (bool) $this->getExpression($node);
		}

		if ($this->isNull($node)) {
			return null;
		}

		return $node->value;
	}

	/**
	 * Returns whether this node is a boolean value
	 * 
	 * @param Node $node
	 *
	 * @return bool
	 */
	private function isBool(Node $node): bool {
		if ($node instanceof ConstFetch) {
			$const = $node->name->parts[0];

			if (isset($this->constMap[$const])) {
				return is_bool($this->constMap[$const]);
			}
		}

		return false;
	}

	/**
	 * Returns whether this node is a null value
	 * 
	 * @param Node $node
	 *
	 * @return bool
	 */
	private function isNull(Node $node): bool {
		if ($node instanceof ConstFetch) {
			$const = $node->name->parts[0];

			return $const === 'null';
		}

		return false;
	}

	/**
	 * Returns the value from a node
	 *
	 * @param Node $node
	 *
	 * @return mixed
	 */
	private function getExpression(Node $node): mixed {
		return match (true) {
			$node instanceof ConstFetch => $this->constMap[$node->name->parts[0]] ?? $node->name->parts[0],
			$node instanceof ClassConstFetch => (!$node->class instanceof Node\Name ?: $node->class->parts[0]) . '::' . $node->name,
			$node instanceof MagicConst => $node->getName(),
			$node instanceof Array_ => (new PrettyPrinter())->prettyPrintExpr($node),
			$node instanceof BinaryOp => $node->getOperatorSigil(),
			default => throw new \RuntimeException('Unexpected `' . $node::class . '` in ' . __METHOD__)
		};
	}
}
