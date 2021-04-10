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
 * Abstract Part
 *
 * Keeps track if the model has a final modifier or not
 *
 * @author Thomas Gossmann
 */
trait FinalPart {

	/** @var bool */
	private bool $final = false;

	/**
	 * Returns whether this is final
	 *
	 * @return bool `true` for final and `false` if not
	 */
	public function isFinal(): bool {
		return $this->final;
	}

	/**
	 * Sets this final
	 *
	 * @param bool $final `true` for final and `false` if not
	 *
	 * @return $this
	 */
	public function setFinal(bool $final): self {
		$this->final = $final;

		return $this;
	}
}
