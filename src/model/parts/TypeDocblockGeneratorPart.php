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
use gossi\docblock\tags\ReturnTag;
use gossi\docblock\tags\VarTag;

/**
 * Type docblock generator part
 *
 * For all models that have a type and need docblock tag generated from it
 *
 * @author Thomas Gossmann
 */
trait TypeDocblockGeneratorPart {

	/**
	 * Returns the docblock
	 *
	 * @return Docblock
	 */
	abstract protected function getDocblock(): Docblock;

	/**
	 * Returns the type
	 *
	 * @return string
	 */
	abstract public function getType(): string;

	/**
	 * Returns the type description
	 *
	 * @return string
	 */
	abstract public function getTypeDescription(): string;

	/**
	 * Generates a type tag (return or var) but checks if one exists and updates this one
	 *
	 * @param ReturnTag|VarTag $tag
	 */
	protected function generateTypeTag(ReturnTag|VarTag $tag): void {
		$docblock = $this->getDocblock();
		$type = $this->getType();

		if (!empty($type)) {

			// try to find tag at first and update
			$tags = $docblock->getTags($tag->getTagName());
			if ($tags->size() > 0) {
				$ttag = $tags->get(0);
				$ttag->setType($this->getType() . ($this->getNullable() ? '|null' : ''));
				$ttag->setDescription($this->getTypeDescription());
			}

			// ... anyway create and append
			else {
				$docblock->appendTag($tag
					->setType($this->getType() . ($this->getNullable() ? '|null' : ''))
					->setDescription($this->getTypeDescription())
				);
			}
		}
	}
}
