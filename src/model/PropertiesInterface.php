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

interface PropertiesInterface {

	/**
	 * Sets a collection of properties
	 *
	 * @param PhpProperty[] $properties
	 *
	 * @return $this
	 */
	public function setProperties(array $properties);

	/**
	 * Adds a property
	 *
	 * @param PhpProperty $property
	 *
	 * @return $this
	 */
	public function setProperty(PhpProperty $property);

	/**
	 * Removes a property
	 *
	 * @param PhpProperty|string $nameOrProperty property name or instance
	 *
	 * @throws \InvalidArgumentException If the property cannot be found
	 *
	 * @return $this
	 */
	public function removeProperty(PhpProperty|string $nameOrProperty);

	/**
	 * Checks whether a property exists
	 *
	 * @param PhpProperty|string $nameOrProperty property name or instance
	 *
	 * @return bool `true` if a property exists and `false` if not
	 */
	public function hasProperty(PhpProperty|string $nameOrProperty): bool;

	/**
	 * Returns a property
	 *
	 * @param string $name property name
	 *
	 * @throws \InvalidArgumentException If the property cannot be found
	 *
	 * @return PhpProperty
	 */
	public function getProperty(string $name): PhpProperty;

	/**
	 * Returns a collection of properties
	 *
	 * @return Map
	 */
	public function getProperties(): Map;

	/**
	 * Returns all property names
	 *
	 * @return Set
	 */
	public function getPropertyNames(): Set;

	/**
	 * Clears all properties
	 *
	 * @return $this
	 */
	public function clearProperties();
}
