<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\model;

use phootwork\collection\Map;
use phootwork\collection\Set;

/**
 * Interface to all php structs that can have constants
 *
 * Implementation is realized in the `ConstantsPart`
 *
 * @author Thomas Gossmann
 */
interface ConstantsInterface {

	/**
	 * Sets a collection of constants
	 *
	 * @param array|PhpConstant[] $constants
	 *
	 * @return $this
	 */
	public function setConstants(array $constants);

	/**
	 * Adds a constant
	 *
	 * @param string|PhpConstant $nameOrConstant constant or name
	 * @param string|int|float|bool|null $value
	 * @param bool $isExpression
	 *
	 * @return $this
	 */
	public function setConstant(string|PhpConstant $nameOrConstant, string|int|float|bool|null $value = null, bool $isExpression = false);

	/**
	 * Removes a constant
	 *
	 * @param string|PhpConstant $nameOrConstant $nameOrConstant constant or name
	 *
	 * @throws \InvalidArgumentException If the constant cannot be found
	 *
	 * @return $this
	 */
	public function removeConstant(PhpConstant|string $nameOrConstant);

	/**
	 * Checks whether a constant exists
	 *
	 * @param string|PhpConstant $nameOrConstant
	 *
	 * @return bool
	 */
	public function hasConstant(PhpConstant|string $nameOrConstant): bool;

	/**
	 * Returns a constant
	 *
	 * @param string $name constant name
	 *
	 * @throws \InvalidArgumentException If the constant cannot be found
	 *
	 * @return PhpConstant
	 */
	public function getConstant(string $name): PhpConstant;

	/**
	 * Returns constants
	 *
	 * @return Map a collection of PhpConstant objects
	 */
	public function getConstants(): Map;

	/**
	 * Returns all constant names
	 *
	 * @return Set a collection of constant names
	 */
	public function getConstantNames(): Set;

	/**
	 * Clears all constants
	 *
	 * @return $this
	 */
	public function clearConstants();
}
