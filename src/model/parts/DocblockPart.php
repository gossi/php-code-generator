<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\model\parts;

use gossi\docblock\Docblock;

/**
 * Docblock Part
 *
 * Setting and getting a docblock on a model
 *
 * @author Thomas Gossmann
 */
trait DocblockPart {

	/** @var Docblock */
	private Docblock $docblock;

	/**
	 * Sets the docblock
	 *
	 * @param Docblock|string $doc
	 *
	 * @return $this
	 */
	public function setDocblock(string|Docblock $doc): self {
		if (is_string($doc)) {
			$doc = new Docblock(trim($doc));
		}
		$this->docblock = $doc;

		return $this;
	}

	/**
	 * Returns the docblock
	 *
	 * @return Docblock
	 */
	public function getDocblock(): Docblock {
		return $this->docblock;
	}
}
