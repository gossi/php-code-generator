<?php  declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\model\parts;

use gossi\codegen\model\PhpParameter;
use gossi\docblock\Docblock;
use gossi\docblock\tags\ParamTag;
use phootwork\collection\ArrayList;

/**
 * Parameters Part
 *
 * For all models that can have parameters
 *
 * @author Thomas Gossmann
 */
trait ParametersPart {
	private ArrayList $parameters;

	private function initParameters() {
		$this->parameters = new ArrayList();
	}

	/**
	 * Sets a collection of parameters
	 *
	 * Note: clears all parameters before setting the new ones
	 *
	 * @param PhpParameter[] $parameters
	 *
	 * @return $this
	 */
	public function setParameters(array $parameters): self {
		$this->parameters->clear();
		array_map([$this, 'addParameter'], $parameters);

		return $this;
	}

	/**
	 * Adds a parameter
	 *
	 * @param PhpParameter $parameter
	 *
	 * @return $this
	 */
	public function addParameter(PhpParameter $parameter): self {
		$this->parameters->add($parameter);

		return $this;
	}

	/**
	 * Checks whether a parameter exists
	 *
	 * @param string|PhpParameter $name parameter name
	 *
	 * @return bool `true` if a parameter exists and `false` if not
	 */
	public function hasParameter(PhpParameter|string $name): bool {
		return $this->parameters->search((string) $name,
			fn (PhpParameter $element, string $query): bool => $element->getName() === $query)
		;
	}

	/**
	 * A quick way to add a parameter which is created from the given parameters
	 *
	 * @param string      $name
	 * @param string 	  $type
	 * @param mixed       $defaultValue omit the argument to define no default value
	 *
	 * @return $this
	 */
	public function addSimpleParameter(string $name, string $type = '', mixed $defaultValue = null): self {
		$parameter = new PhpParameter($name);
		$parameter->setType($type);

		if (2 < func_num_args()) {
			$parameter->setValue($defaultValue);
		}

		$this->addParameter($parameter);

		return $this;
	}

	/**
	 * A quick way to add a parameter with description which is created from the given parameters
	 *
	 * @param string      $name
	 * @param string $type
	 * @param string $typeDescription
	 * @param mixed       $defaultValue omit the argument to define no default value
	 *
	 * @return $this
	 */
	public function addSimpleDescParameter(string $name, string $type = '', string $typeDescription = '', mixed $defaultValue = null): self {
		$parameter = new PhpParameter($name);
		$parameter->setType($type);
		$parameter->setTypeDescription($typeDescription);

		if (3 < func_num_args() == 3) {
			$parameter->setValue($defaultValue);
		}

		$this->addParameter($parameter);

		return $this;
	}

	/**
	 * Returns a parameter by index or name
	 *
	 * @param string|int $nameOrIndex
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return PhpParameter
	 */
	public function getParameter(int|string $nameOrIndex): PhpParameter {
		$param = is_int($nameOrIndex) ? $this->parameters->get($nameOrIndex) :
			$this->parameters->find($nameOrIndex,
				fn (PhpParameter $element, string $query): bool => $element->getName() === $query
			)
		;

		if ($param === null) {
			throw new \InvalidArgumentException(
				'There is no parameter ' . is_int($nameOrIndex) ? "having index $nameOrIndex" : "named `$nameOrIndex`"
			);
		}

		return $param;
	}

	/**
	 * Replaces a parameter at a given position
	 *
	 * @param int $position
	 * @param PhpParameter $parameter
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return $this
	 */
	public function replaceParameter(int $position, PhpParameter $parameter) {
		if ($position < 0 || $position > $this->parameters->count()) {
			throw new \InvalidArgumentException(sprintf('The position must be in the range [0, %d].', $this->parameters->size()));
		}
		$this->parameters->removeByIndex($position)->insert($parameter, $position);

		return $this;
	}

	/**
	 * Remove a parameter at a given position
	 *
	 * @param int|string|PhpParameter $param
	 *
	 * @return $this
	 */
	public function removeParameter(int|string|PhpParameter $param): self {
		if (!is_int($param)) {
			$param = $this->parameters->findIndex((string) $param,
				fn (PhpParameter $element, string $query): bool => $element->getName() === $query);
		}

		if ($param === null || $this->parameters->get($param) === null) {
			throw new \InvalidArgumentException("The parameter with index `$param` does not exists");
		}

		$this->parameters->removeByIndex($param);

		return $this;
	}

	/**
	 * Returns a collection of parameters
	 *
	 * @return ArrayList
	 */
	public function getParameters(): ArrayList {
		return $this->parameters;
	}

	/**
	 * Returns the docblock
	 *
	 * @return Docblock
	 */
	abstract protected function getDocblock(): Docblock;

	/**
	 * Generates docblock for params
	 */
	protected function generateParamDocblock(): void {
		$docblock = $this->getDocblock();
		$tags = $docblock->getTags('param');
		//@todo $this->>parameters->each(...)
		foreach ($this->parameters as $param) {
			$ptag = $param->getDocblockTag();

			$tag = $tags->find($ptag,
				fn (ParamTag $tag, ParamTag $ptag): bool => $tag->getVariable() == $ptag->getVariable()
			);

			// try to update existing docblock first
			if ($tag !== null) {
				$tag->setDescription($ptag->getDescription());
				$tag->setType($ptag->getType());
			} // ... append if it doesn't exist
			else {
				$docblock->appendTag($ptag);
			}
		}
	}
}
