<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\model\parts;

use gossi\codegen\model\PhpConstant;

/**
 * Value part
 *
 * For all models that have a value (or expression)
 *
 * @author Thomas Gossmann
 */
trait ValuePart {
	private string|int|float|null|bool|PhpConstant $value = null;

	/** @var bool */
	private bool $hasValue = false;

	/** @var mixed */
	private mixed $expression = null;

	/** @var bool */
	private bool $hasExpression = false;

	/**
	 * Sets the value
	 *
	 * @param string|int|float|bool|null|PhpConstant $value
	 *
	 * @throws \InvalidArgumentException if the value is not an accepted primitve
	 *
	 * @return $this
	 */
	public function setValue(string|int|float|null|bool|PhpConstant $value): self {
		$this->value = $value;
		$this->hasValue = true;

		return $this;
	}

	/**
	 * Unsets the value
	 *
	 * @return $this
	 */
	public function unsetValue(): self {
		$this->value = null;
		$this->hasValue = false;

		return $this;
	}

	/**
	 * Returns the value
	 *
	 * @return string|int|float|bool|null|PhpConstant
	 */
	public function getValue(): string|int|float|bool|null|PhpConstant {
		return $this->value;
	}

	/**
	 * Checks whether a value or expression is set
	 *
	 * @return bool
	 */
	public function hasValue(): bool {
		return $this->hasValue || $this->hasExpression;
	}

	/**
	 * Returns whether an expression is set
	 *
	 * @return bool
	 */
	public function isExpression(): bool {
		return $this->hasExpression;
	}

	/**
	 * Sets an expression
	 *
	 * @param mixed $expr
	 *
	 * @return $this
	 */
	public function setExpression(mixed $expr): self {
		$this->expression = $expr;
		$this->hasExpression = true;

		return $this;
	}

	/**
	 * Returns the expression
	 *
	 * @return mixed
	 */
	public function getExpression(): mixed {
		return $this->expression;
	}

	/**
	 * Unsets the expression
	 *
	 * @return $this
	 */
	public function unsetExpression(): self {
		$this->expression = null;
		$this->hasExpression = false;

		return $this;
	}
}
