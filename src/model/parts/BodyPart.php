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
 * Body Part
 *
 * For all models that do have a code body
 *
 * @author Thomas Gossmann
 */
trait BodyPart {

	/** @var string */
	private string $body = '';

	/**
	 * Sets the body for this
	 *
	 * @param string $body
	 *
	 * @return $this
	 */
	public function setBody(string $body): self {
		$this->body = $body;

		return $this;
	}

	/**
	 * Returns the body
	 *
	 * @return string
	 */
	public function getBody(): string {
		return $this->body;
	}
}
