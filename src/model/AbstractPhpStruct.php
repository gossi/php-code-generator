<?php declare(strict_types=1);
/*
 * Copyright 2011 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace gossi\codegen\model;

use gossi\codegen\model\parts\DocblockPart;
use gossi\codegen\model\parts\LongDescriptionPart;
use gossi\codegen\model\parts\QualifiedNamePart;
use gossi\docblock\Docblock;
use phootwork\collection\Map;
use phootwork\collection\Set;

/**
 * Represents an abstract php structure (class, trait or interface).
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 * @author Thomas Gossmann
 */
abstract class AbstractPhpStruct extends AbstractModel implements NamespaceInterface, DocblockInterface {
	use DocblockPart;
	use LongDescriptionPart;
	use QualifiedNamePart;

	/** @var Map */
	private Map $useStatements;

	/** @var Set */
	private Set $requiredFiles;

	/** @var Map */
	private Map $methods;

	/**
	 * Creates a new struct
	 *
	 * @param string $name the fqcn
	 *
	 * @return static
	 */
	public static function create(string $name = ''): self {
		return new static($name);
	}

	/**
	 * Creates a new struct
	 *
	 * @param string $name the fqcn
	 */
	public function __construct(string $name = '') {
		$this->setQualifiedName($name);
		$this->docblock = new Docblock();
		$this->useStatements = new Map();
		$this->requiredFiles = new Set();
		$this->methods = new Map();
	}

	/**
	 * Sets requried files
	 *
	 * @param array|Set $files
	 *
	 * @return $this
	 */
	public function setRequiredFiles(array|Set $files): self {
		$this->requiredFiles = is_array($files) ? new Set($files) : $files;

		return $this;
	}

	/**
	 * Adds a new required file
	 *
	 * @param string $file
	 *
	 * @return $this
	 */
	public function addRequiredFile(string $file): self {
		$this->requiredFiles->add($file);

		return $this;
	}

	/**
	 * Returns required files
	 *
	 * @return Set collection of filenames
	 */
	public function getRequiredFiles(): Set {
		return $this->requiredFiles;
	}

	/**
	 * Sets use statements
	 *
	 * @see #addUseStatement
	 * @see #declareUses()
	 *
	 * @param array $useStatements
	 *
	 * @return $this
	 */
	public function setUseStatements(array $useStatements): self {
		$this->useStatements->clear();
		foreach ($useStatements as $alias => $useStatement) {
			$this->addUseStatement($useStatement, $alias);
		}

		return $this;
	}

	/**
	 * Adds a use statement with an optional alias
	 *
	 * @param string $qualifiedName
	 * @param string $alias
	 *
	 * @return $this
	 */
	public function addUseStatement(string $qualifiedName, string $alias = ''): self {
		if ($alias === '') {
			$alias = str_contains($qualifiedName, '\\') ?
				substr($qualifiedName, strrpos($qualifiedName, '\\') + 1) : $qualifiedName;
		}

		$this->useStatements->set($alias, $qualifiedName);

		return $this;
	}

	/**
	 * Clears all use statements
	 *
	 * @return $this
	 */
	public function clearUseStatements(): self {
		$this->useStatements->clear();

		return $this;
	}

	/**
	 * Declares multiple use statements at once.
	 *
	 * @param string ...$uses use statements multiple qualified names
	 *
	 * @return $this
	 */
	public function declareUses(string ...$uses): self {
		foreach ($uses as $name) {
			$this->declareUse($name);
		}

		return $this;
	}

	/**
	 * Declares a "use $fullClassName" with " as $alias" if $alias is available,
	 * and returns its alias (or not qualified classname) to be used in your actual
	 * php code.
	 *
	 * If the class has already been declared you get only the set alias.
	 *
	 * @param string $qualifiedName
	 * @param string $alias
	 *
	 * @return string the used alias
	 */
	public function declareUse(string $qualifiedName, string $alias = ''): string {
		$qualifiedName = trim($qualifiedName, '\\');
		if (!$this->hasUseStatement($qualifiedName)) {
			$this->addUseStatement($qualifiedName, $alias);
		}

		return $this->getUseAlias($qualifiedName);
	}

	/**
	 * Returns whether the given use statement is present
	 *
	 * @param string $qualifiedName
	 *
	 * @return bool
	 */
	public function hasUseStatement(string $qualifiedName): bool {
		return $this->useStatements->contains($qualifiedName);
	}

	public function hasUseStatementsToRender(): bool {
		if ($this->useStatements->isEmpty()) {
			return false;
		}

		return $this->useStatements->search(
			fn (string $stmt) => str_contains($stmt, '\\') || $this->getNamespace() !== ''
		);
	}

	/**
	 * Returns the usable alias for a qualified name
	 *
	 * @param string $qualifiedName
	 *
	 * @return string the alias
	 */
	public function getUseAlias(string $qualifiedName): string {
		return $this->useStatements->getKey($qualifiedName) ?? '';
	}

	/**
	 * Removes a use statement
	 *
	 * @param string $qualifiedName
	 *
	 * @return $this
	 */
	public function removeUseStatement(string $qualifiedName): self {
		if ($this->useStatements->contains($qualifiedName)) {
			$this->useStatements->remove($this->useStatements->getKey($qualifiedName));
		}

		return $this;
	}

	/**
	 * Returns all use statements
	 *
	 * @return Map collection of use statements
	 */
	public function getUseStatements(): Map {
		return $this->useStatements;
	}

	/**
	 * Sets a collection of methods
	 *
	 * @param PhpMethod[] $methods
	 *
	 * @return $this
	 */
	public function setMethods(array $methods): self {
		$this->clearMethods();
		foreach ($methods as $method) {
			$this->setMethod($method);
		}

		return $this;
	}

	/**
	 * Adds a method
	 *
	 * @param PhpMethod $method
	 *
	 * @return $this
	 */
	public function setMethod(PhpMethod $method): self {
		$method->setParent($this);
		$this->methods->set($method->getName(), $method);

		return $this;
	}

	/**
	 * Removes a method
	 *
	 * @param string|PhpMethod $nameOrMethod method name or Method instance
	 *
	 * @throws \InvalidArgumentException if the method cannot be found
	 *
	 * @return $this
	 */
	public function removeMethod(string|PhpMethod $nameOrMethod): self {
		$name = (string) $nameOrMethod;

		if (!$this->methods->has($name)) {
			throw new \InvalidArgumentException("The method `$name` does not exist.");
		}
		$m = $this->methods->get($name);
		$m->setParent(null);
		$this->methods->remove($name);

		return $this;
	}

	/**
	 * Checks whether a method exists or not
	 *
	 * @param string|PhpMethod $nameOrMethod method name or Method instance
	 *
	 * @return bool `true` if it exists and `false` if not
	 */
	public function hasMethod(string|PhpMethod $nameOrMethod): bool {
		return $this->methods->has((string) $nameOrMethod);
	}

	/**
	 * Returns a method
	 *
	 * @param string $name the methods name
	 *
	 * @throws \InvalidArgumentException if the method cannot be found
	 *
	 * @return PhpMethod
	 */
	public function getMethod(string $name): PhpMethod {
		if (!$this->methods->has($name)) {
			throw new \InvalidArgumentException("The method `$name` does not exist.");
		}

		return $this->methods->get($name);
	}

	/**
	 * Returns all methods
	 *
	 * @return Map collection of methods
	 */
	public function getMethods(): Map {
		return $this->methods;
	}

	/**
	 * Returns all method names
	 *
	 * @return Set
	 */
	public function getMethodNames(): Set {
		return $this->methods->keys();
	}

	/**
	 * Clears all methods
	 *
	 * @return $this
	 */
	public function clearMethods(): self {
		$this->methods->each(fn (string $key, PhpMethod $method) => $method->setParent(null));
		$this->methods->clear();

		return $this;
	}

	/**
	 * Generates a docblock from provided information
	 */
	public function generateDocblock(): void {
		$docblock = $this->getDocblock();
		$docblock->setShortDescription($this->getDescription());
		$docblock->setLongDescription($this->getLongDescription());
		$this->methods->each(fn (string $key, PhpMethod $method) => $method->generateDocblock());
	}
}
