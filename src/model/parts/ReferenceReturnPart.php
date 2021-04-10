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
 * Reference return part
 *
 * Keeps track whether the return value is a reference or not
 *
 * @author Thomas Gossmann
 */
trait ReferenceReturnPart {

	/** @var bool */
	private bool $referenceReturned = false;

	/**
	 * Set true if a reference is returned of false if not
	 *
	 * @param bool $referenceReturned
	 *
	 * @return $this
	 */
	public function setReferenceReturned(bool $referenceReturned): self {
		$this->referenceReturned = $referenceReturned;

		return $this;
	}

	/**
	 * Returns whether a reference is returned
	 *
	 * @return bool
	 */
	public function isReferenceReturned(): bool {
		return $this->referenceReturned;
	}
}
