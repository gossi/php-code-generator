<?php declare(strict_types=1);
/*
 * Copyright 2011 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace gossi\codegen\model;

use gossi\codegen\model\parts\DocblockPart;
use gossi\codegen\model\parts\LongDescriptionPart;
use gossi\codegen\model\parts\NamePart;
use gossi\codegen\model\parts\TypePart;
use gossi\docblock\Docblock;

/**
 * Abstract PHP member class.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 * @author Thomas Gossmann
 */
abstract class AbstractPhpMember extends AbstractModel implements DocblockInterface {
	use DocblockPart;
	use LongDescriptionPart;
	use NamePart;
	use TypePart;

	/**
	 * Private visibility
	 *
	 * @var string
	 */
	const VISIBILITY_PRIVATE = 'private';

	/**
	 * Protected visibility
	 *
	 * @var string
	 */
	const VISIBILITY_PROTECTED = 'protected';

	/**
	 * Public visibility
	 *
	 * @var string
	 */
	const VISIBILITY_PUBLIC = 'public';

	/** @var bool */
	private bool $static = false;

	/** @var string */
	private string $visibility = self::VISIBILITY_PUBLIC;

	/** @var ?AbstractPhpStruct */
	private ?AbstractPhpStruct $parent = null;

	/**
	 * Creates a new member
	 *
	 * @param string $name the name of the member
	 */
	public function __construct(string $name) {
		$this->setName($name);
		$this->docblock = new Docblock();
	}

	/**
	 * Sets the members visibility
	 *
	 * @see self::VISIBILITY_PUBLIC
	 * @see self::VISIBILITY_PROTECTED
	 * @see self::VISIBILITY_PRIVATE
	 *
	 * @param string $visibility the new visibility
	 *
	 * @return $this
	 */
	public function setVisibility(string $visibility): self {
		if ($visibility !== self::VISIBILITY_PRIVATE
				&& $visibility !== self::VISIBILITY_PROTECTED
				&& $visibility !== self::VISIBILITY_PUBLIC) {
			throw new \InvalidArgumentException(sprintf('The visibility "%s" does not exist.', $visibility));
		}

		$this->visibility = $visibility;

		return $this;
	}

	/**
	 * Returns the visibility state of this member
	 *
	 * @return string the visibility
	 */
	public function getVisibility(): string {
		return $this->visibility;
	}

	/**
	 * Sets whether or not this member is static
	 *
	 * @param bool $static
	 *
	 * @return $this
	 */
	public function setStatic(bool $static): self {
		$this->static = $static;

		return $this;
	}

	/**
	 * Returns whether this member is static
	 *
	 * @return bool `true` if static and `false` if not
	 */
	public function isStatic(): bool {
		return $this->static;
	}

	/**
	 * Sets the parent structure to which this member belongs
	 *
	 * @internal
	 *
	 * @param AbstractPhpStruct|null $parent
	 *
	 * @return $this
	 */
	public function setParent(?AbstractPhpStruct $parent): self {
		$this->parent = $parent;

		return $this;
	}

	/**
	 * Returns the parent structure to which this member belongs
	 *
	 * @internal
	 *
	 * @return ?AbstractPhpStruct
	 */
	public function getParent(): ?AbstractPhpStruct {
		return $this->parent;
	}
}
