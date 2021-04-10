<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\model;

/**
 * Represents models that have a namespace
 *
 * @author Thomas Gossmann
 */
interface NamespaceInterface {

	/**
	 * Sets the namespace
	 *
	 * @param string $namespace
	 *
	 * @return $this
	 */
	public function setNamespace(string $namespace);

	/**
	 * Returns the namespace
	 *
	 * @return string
	 */
	public function getNamespace(): string;
}
