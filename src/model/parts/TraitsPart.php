<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\model\parts;

use gossi\codegen\model\PhpTrait;
use phootwork\collection\Set;

/**
 * Traits part
 *
 * For all models that can have traits
 *
 * @author Thomas Gossmann
 */
trait TraitsPart {

	/** @var Set */
	private Set $traits;

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

	protected function initTraits(): void {
		$this->traits = new Set();
	}

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
	public function addTrait(PhpTrait|string $trait): self {
		if ($trait instanceof PhpTrait) {
			$name = $trait->getName();
			$qname = $trait->getQualifiedName();
			$namespace = $trait->getNamespace();

			if ($namespace != $this->getNamespace()) {
				$this->addUseStatement($qname);
			}
		} else {
			$name = $trait;
		}

		$this->traits->add($name);

		return $this;
	}

	/**
	 * Returns a collection of traits
	 *
	 * @return Set
	 */
	public function getTraits(): Set {
		return $this->traits;
	}

	/**
	 * Checks whether a trait exists
	 *
	 * @param PhpTrait|string $trait
	 *
	 * @return bool `true` if it exists and `false` if not
	 */
	public function hasTrait(PhpTrait|string $trait): bool {
		if ($trait instanceof PhpTrait) {
			return $this->traits->contains($trait->getName())
				|| $this->traits->contains($trait->getQualifiedName());
		}

		return $this->traits->contains($trait) || $this->hasTrait(new PhpTrait($trait));
	}

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
	public function removeTrait(PhpTrait|string $trait): self {
		if ($trait instanceof PhpTrait) {
			$name = $trait->getName();
			$qname = $trait->getQualifiedName();

			$this->removeUseStatement($qname);
		} else {
			$name = $trait;
		}

		$this->traits->remove($name);

		return $this;
	}

	/**
	 * Sets a collection of traits
	 *
	 * @param PhpTrait[]|string[] $traits
	 *
	 * @return $this
	 */
	public function setTraits(array $traits): self {
		array_map([$this, 'addTrait'], $traits);

		return $this;
	}
}
