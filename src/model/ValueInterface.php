<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\model;

interface ValueInterface {

	/**
	 * Sets the value
	 *
	 * @param string|int|float|null|bool|PhpConstant $value
	 *
	 * @return $this
	 */
	public function setValue(string|int|float|null|bool|PhpConstant $value);

	/**
	 * Unsets the value
	 *
	 * @return $this
	 */
	public function unsetValue();

	/**
	 * Returns the value
	 *
	 * @return string|int|float|null|bool|PhpConstant
	 */
	public function getValue(): string|int|float|null|bool|PhpConstant;

	/**
	 * Checks whether a value or expression is set
	 *
	 * @return bool
	 */
	public function hasValue(): bool;

	/**
	 * Returns whether an expression is set
	 *
	 * @return bool
	 */
	public function isExpression(): bool;

	/**
	 * Sets an expression
	 *
	 * @param string $expr
	 *
	 * @return $this
	 */
	public function setExpression(string $expr);

	/**
	 * Returns the expression
	 *
	 * @return mixed
	 */
	public function getExpression(): mixed;

	/**
	 * Unsets the expression
	 *
	 * @return $this
	 */
	public function unsetExpression();
}
