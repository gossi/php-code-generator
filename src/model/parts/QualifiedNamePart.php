<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\model\parts;

use phootwork\lang\Text;

/**
 * Qualified name part
 *
 * For all models that have a name and namespace
 *
 * @author Thomas Gossmann
 */
trait QualifiedNamePart {
	use NamePart;

	/** @var string */
	private string $namespace = '';

	/**
	 * Sets the namespace
	 *
	 * @param string $namespace
	 *
	 * @return $this
	 */
	public function setNamespace(string $namespace = ''): self {
		$this->namespace = $namespace;

		return $this;
	}

	/**
	 * In contrast to setName(), this method accepts the fully qualified name
	 * including the namespace.
	 *
	 * @param string $fullName
	 *
	 * @return $this
	 */
	public function setQualifiedName(string $fullName = ''): self {
		$fullName = new Text($fullName);

		if ($fullName->isEmpty()) {
			return $this;
		}

		$this->name = $fullName->substring((int) $fullName->lastIndexOf('\\'))->trimStart('\\')->toString();
		$this->namespace = $fullName->trimEnd($this->name)->trim('\\')->toString();

		return $this;
	}

	/**
	 * Returns the namespace
	 *
	 * @return string
	 */
	public function getNamespace(): string {
		return $this->namespace;
	}

	/**
	 * Returns the qualified name
	 *
	 * @return string
	 */
	public function getQualifiedName(): string {
		if ($this->namespace) {
			return $this->namespace . '\\' . $this->name;
		}

		return $this->name;
	}
}
