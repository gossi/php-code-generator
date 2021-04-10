<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\model\parts;

use gossi\codegen\model\PhpProperty;
use InvalidArgumentException;
use phootwork\collection\Map;
use phootwork\collection\Set;

/**
 * Properties part
 *
 * For all models that can have properties
 *
 * @author Thomas Gossmann
 */
trait PropertiesPart {

	/** @var Map */
	private Map $properties;

	private function initProperties() {
		$this->properties = new Map();
	}

	/**
	 * Sets a collection of properties
	 *
	 * @param PhpProperty[] $properties
	 *
	 * @return $this
	 */
	public function setProperties(array $properties): self {
		$this->properties->each(fn (string $key, PhpProperty $prop) => $prop->setParent(null));
		$this->properties->clear();

		array_map([$this, 'setProperty'], $properties);

		return $this;
	}

	/**
	 * Adds a property
	 *
	 * @param PhpProperty $property
	 *
	 * @return $this
	 */
	public function setProperty(PhpProperty $property): self {
		/** AbstractPhpStruct $this */
		$property->setParent($this);
		$this->properties->set($property->getName(), $property);

		return $this;
	}

	/**
	 * Removes a property
	 *
	 * @param PhpProperty|string $nameOrProperty property name or instance
	 *
	 * @throws InvalidArgumentException If the property cannot be found
	 *
	 * @return $this
	 */
	public function removeProperty(PhpProperty|string $nameOrProperty): self {
		$name = (string) $nameOrProperty;

		if (!$this->properties->has($name)) {
			throw new InvalidArgumentException("The property `$name` does not exist.");
		}

		$this->properties->get($name)->setParent(null);
		$this->properties->remove($name);

		return $this;
	}

	/**
	 * Checks whether a property exists
	 *
	 * @param PhpProperty|string $nameOrProperty property name or instance
	 *
	 * @return bool `true` if a property exists and `false` if not
	 */
	public function hasProperty(PhpProperty|string $nameOrProperty): bool {
		return $this->properties->has((string) $nameOrProperty);
	}

	/**
	 * Returns a property
	 *
	 * @param string $name property name
	 *
	 * @throws InvalidArgumentException If the property cannot be found
	 *
	 * @return PhpProperty
	 */
	public function getProperty(string $name): PhpProperty {
		if (!$this->properties->has($name)) {
			throw new InvalidArgumentException(sprintf('The property "%s" does not exist.', $name));
		}

		return $this->properties->get($name);
	}

	/**
	 * Returns a collection of properties
	 *
	 * @return Map
	 */
	public function getProperties(): Map {
		return $this->properties;
	}

	/**
	 * Returns all property names
	 *
	 * @return Set
	 */
	public function getPropertyNames(): Set {
		return $this->properties->keys();
	}

	/**
	 * Clears all properties
	 *
	 * @return $this
	 */
	public function clearProperties(): self {
		$this->properties->each(fn (string $key, PhpProperty $elem) => $elem->setParent(null));
		$this->properties->clear();

		return $this;
	}
}
