<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\model\parts;

use gossi\codegen\model\PhpInterface;
use phootwork\collection\Set;

/**
 * Interfaces part
 *
 * For all models that can contain interfaces
 *
 * @author Thomas Gossmann
 */
trait InterfacesPart {

	/** @var Set */
	private Set $interfaces;

	private function initInterfaces() {
		$this->interfaces = new Set();
	}

	/**
	 * Adds a use statement with an optional alias
	 *
	 * @param string $qualifiedName
	 * @param string $alias
	 *
	 * @return $this
	 */
	abstract public function addUseStatement(string $qualifiedName, string $alias = '');

	/**
	 * Removes a use statement
	 *
	 * @param string $qualifiedName
	 *
	 * @return $this
	 */
	abstract public function removeUseStatement(string $qualifiedName);

	/**
	 * Returns the namespace
	 *
	 * @return string
	 */
	abstract public function getNamespace(): string;

	/**
	 * Adds an interface.
	 *
	 * If the interface is passed as PhpInterface object,
	 * the interface is also added as use statement.
	 *
	 * @param PhpInterface|string $interface interface or qualified name
	 *
	 * @return $this
	 */
	public function addInterface(PhpInterface|string $interface) {
		if ($interface instanceof PhpInterface) {
			$name = $interface->getName();
			$qname = $interface->getQualifiedName();
			$namespace = $interface->getNamespace();

			if ($namespace != $this->getNamespace()) {
				$this->addUseStatement($qname);
			}
		} else {
			$name = $interface;
		}

		$this->interfaces->add($name);

		return $this;
	}

	/**
	 * Returns the interfaces
	 *
	 * @return Set
	 */
	public function getInterfaces(): Set {
		return $this->interfaces;
	}

	/**
	 * Checks whether interfaces exists
	 *
	 * @return bool `true` if interfaces are available and `false` if not
	 */
	public function hasInterfaces(): bool {
		return !$this->interfaces->isEmpty();
	}

	/**
	 * Checks whether an interface exists
	 *
	 * @param PhpInterface|string $interface interface name or instance
	 *
	 * @return bool
	 */
	public function hasInterface(PhpInterface|string $interface): bool {
		if ($interface instanceof PhpInterface) {
			return $this->interfaces->contains($interface->getName())
				|| $this->interfaces->contains($interface->getQualifiedName());
		}

		return $this->interfaces->contains($interface) || $this->hasInterface(new PhpInterface($interface));
	}

	/**
	 * Removes an interface.
	 *
	 * If the interface is passed as PhpInterface object,
	 * the interface is also remove from the use statements.
	 *
	 * @param PhpInterface|string $interface interface or qualified name
	 *
	 * @return $this
	 */
	public function removeInterface(PhpInterface|string $interface): self {
		if ($interface instanceof PhpInterface) {
			$name = $interface->getName();
			$qname = $interface->getQualifiedName();

			$this->removeUseStatement($qname);
		} else {
			$name = $interface;
		}

		$this->interfaces->remove($name);

		return $this;
	}

	/**
	 * Sets a collection of interfaces
	 *
	 * @param string[]|PhpInterface[] $interfaces
	 *
	 * @return $this
	 */
	public function setInterfaces(array $interfaces): self {
		array_map([$this, 'addInterface'], $interfaces);

		return $this;
	}
}
