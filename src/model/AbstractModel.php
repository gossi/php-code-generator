<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\model;

use Stringable;

/**
 * Parent of all models
 *
 * @author Thomas Gossmann
 */
abstract class AbstractModel implements Stringable {

	/** @var string */
	protected string $description = '';

	abstract public function getName(): string;

	/**
	 * Returns this description
	 *
	 * @return string
	 */
	public function getDescription(): string {
		return $this->description;
	}

	/**
	 * Sets the description, which will also be used when generating a docblock
	 *
	 * @param string|array $description
	 *
	 * @return $this
	 */
	public function setDescription(string|array $description): self {
		if (is_array($description)) {
			$description = implode("\n", $description);
		}
		$this->description = $description;

		return $this;
	}

	public function __toString(): string {
		return $this->getName();
	}
}
