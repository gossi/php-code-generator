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
 * Keeps track if the model has an abstract modifier or not
 *
 * @author Thomas Gossmann
 */
trait AbstractPart {

	/** @var bool */
	private bool $abstract = false;

	/**
	 * Returns whether this is abstract
	 *
	 * @return bool `true` for abstract and `false` if not
	 */
	public function isAbstract(): bool {
		return $this->abstract;
	}

	/**
	 * Sets this to abstract
	 *
	 * @param bool $abstract `true` for abstract and `false` if not
	 *
	 * @return $this
	 */
	public function setAbstract(bool $abstract): self {
		$this->abstract = $abstract;

		return $this;
	}
}
