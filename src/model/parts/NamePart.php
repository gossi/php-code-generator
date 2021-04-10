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
 * Name part
 *
 * For all models that do have a name
 *
 * @author Thomas Gossmann
 */
trait NamePart {

	/** @var string */
	private string $name = '';

	/**
	 * Sets the name
	 *
	 * @param string $name
	 *
	 * @return $this
	 */
	public function setName(string $name = ''): self {
		$this->name = $name;

		return $this;
	}

	/**
	 * Returns the name
	 *
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
}
