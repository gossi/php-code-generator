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

use gossi\codegen\model\parts\TypeDocblockGeneratorPart;
use gossi\codegen\model\parts\TypePart;
use gossi\codegen\model\parts\ValuePart;
use gossi\docblock\tags\VarTag;

/**
 * Represents a PHP property.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 * @author Thomas Gossmann
 */
class PhpProperty extends AbstractPhpMember implements ValueInterface, GenerateableInterface {
	use TypeDocblockGeneratorPart;
	use ValuePart;
	use TypePart;

	/**
	 * Creates a new PHP property
	 *
	 * @param string $name the properties name
	 *
	 * @return static
	 */
	public static function create(string $name): static {
		return new static($name);
	}

	/**
	 * Generates docblock based on provided information
	 */
	public function generateDocblock(): void {
		$docblock = $this->getDocblock();
		$docblock->setShortDescription($this->getDescription());
		$docblock->setLongDescription($this->getLongDescription());

		// var tag
		$this->generateTypeTag(new VarTag());
	}
}
