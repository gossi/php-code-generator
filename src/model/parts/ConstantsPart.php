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
use phootwork\collection\Map;
use phootwork\collection\Set;

/**
 * Constants part
 *
 * For all models that can contain constants
 *
 * @author Thomas Gossmann
 */
trait ConstantsPart {

	/** @var Map */
	private Map $constants;

	private function initConstants() {
		$this->constants = new Map();
	}

	/**
	 * Sets a collection of constants
	 *
	 * @param array|PhpConstant[] $constants
	 *
	 * @return $this
	 */
	public function setConstants(array $constants): self {
		$normalizedConstants = [];
		foreach ($constants as $name => $value) {
			if ($value instanceof PhpConstant) {
				$name = $value->getName();
			} else {
				$constValue = $value;
				$value = new PhpConstant($name);
				$value->setValue($constValue);
			}

			$normalizedConstants[$name] = $value;
		}

		$this->constants->setAll($normalizedConstants);

		return $this;
	}

	/**
	 * Adds a constant
	 *
	 * @param string|PhpConstant $nameOrConstant constant name or instance
	 * @param string|int|float|bool|null $value
	 * @param bool $isExpression whether the value is an expression or not
	 *
	 * @return $this
	 */
	public function setConstant(PhpConstant|string $nameOrConstant, string|int|float|bool|null $value = '', bool $isExpression = false): self {
		if ($nameOrConstant instanceof PhpConstant) {
			$name = $nameOrConstant->getName();
			$constant = $nameOrConstant;
		} else {
			$name = $nameOrConstant;
			$constant = new PhpConstant($nameOrConstant, $value, $isExpression);
		}

		$this->constants->set($name, $constant);

		return $this;
	}

	/**
	 * Removes a constant
	 *
	 * @param string|PhpConstant $nameOrConstant constant name
	 *
	 * @throws \InvalidArgumentException If the constant cannot be found
	 *
	 * @return $this
	 */
	public function removeConstant(PhpConstant|string $nameOrConstant): self {
		if (!$this->constants->has((string) $nameOrConstant)) {
			throw new \InvalidArgumentException(sprintf('The constant "%s" does not exist.', $nameOrConstant));
		}

		$this->constants->remove((string) $nameOrConstant);

		return $this;
	}

	/**
	 * Checks whether a constant exists
	 *
	 * @param string|PhpConstant $nameOrConstant
	 *
	 * @return bool
	 */
	public function hasConstant(PhpConstant|string $nameOrConstant): bool {
		return $this->constants->has((string) $nameOrConstant);
	}

	/**
	 * Returns a constant.
	 *
	 * @param string $name
	 *
	 * @throws \InvalidArgumentException If the constant cannot be found
	 *
	 * @return PhpConstant
	 */
	public function getConstant(string $name): PhpConstant {
		if (!$this->constants->has($name)) {
			throw new \InvalidArgumentException(sprintf('The constant "%s" does not exist.', $name));
		}

		return $this->constants->get($name);
	}

	/**
	 * Returns all constants
	 *
	 * @return Map
	 */
	public function getConstants(): Map {
		return $this->constants;
	}

	/**
	 * Returns all constant names
	 *
	 * @return Set
	 */
	public function getConstantNames(): Set {
		return $this->constants->keys();
	}

	/**
	 * Clears all constants
	 *
	 * @return $this
	 */
	public function clearConstants(): self {
		$this->constants->clear();

		return $this;
	}
}
