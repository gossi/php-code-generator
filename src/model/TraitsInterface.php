<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\model;

use phootwork\collection\Set;

/**
 * Represents models that can have traits
 *
 * @author Thomas Gossmann
 */
interface TraitsInterface {

	/**
	 * Adds a trait.
	 *
	 * If the trait is passed as PhpTrait object,
	 * the trait is also added as use statement.
	 *
	 * @param PhpTrait|string $trait trait or qualified name
	 *
	 * @return $this
	 */
	public function addTrait(PhpTrait|string $trait);

	/**
	 * Returns a collection of traits
	 *
	 * @return Set
	 */
	public function getTraits(): Set;

	/**
	 * Checks whether a trait exists
	 *
	 * @param PhpTrait|string $trait
	 *
	 * @return bool `true` if it exists and `false` if not
	 */
	public function hasTrait(PhpTrait|string $trait): bool;

	/**
	 * Removes a trait.
	 *
	 * If the trait is passed as PhpTrait object,
	 * the trait is also removed from use statements.
	 *
	 * @param PhpTrait|string $trait trait or qualified name
	 *
	 * @return $this
	 */
	public function removeTrait(PhpTrait|string $trait);

	/**
	 * Sets a collection of traits
	 *
	 * @param PhpTrait[] $traits
	 *
	 * @return $this
	 */
	public function setTraits(array $traits);
}
