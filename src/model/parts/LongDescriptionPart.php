<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\model\parts;

/**
 * Long description part
 *
 * For all models that can have an additional long description
 *
 * @author Thomas Gossmann
 */
trait LongDescriptionPart {

	/** @var string */
	private string $longDescription = '';

	/**
	 * Returns the long description
	 *
	 * @return string
	 */
	public function getLongDescription(): string {
		return $this->longDescription;
	}

	/**
	 * Sets the long description
	 *
	 * @param string $longDescription
	 *
	 * @return $this
	 */
	public function setLongDescription(string $longDescription): self {
		$this->longDescription = $longDescription;

		return $this;
	}
}
