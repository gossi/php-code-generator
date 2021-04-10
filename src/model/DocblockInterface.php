<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\model;

use gossi\docblock\Docblock;

/**
 * Docblock interface to represent models that have a docblock
 *
 * Implementation is realized in the `DocblockPart`
 *
 * @author Thomas Gossmann
 */
interface DocblockInterface {

	/**
	 * Sets a docblock
	 *
	 * @param Docblock|string $doc
	 *
	 * @return $this
	 */
	public function setDocblock(string|Docblock $doc);

	/**
	 * Returns a docblock
	 *
	 * @return Docblock
	 */
	public function getDocblock(): Docblock;

	/**
	 * Generates a docblock from provided information
	 *
	 * @return $this
	 */
	public function generateDocblock();
}
