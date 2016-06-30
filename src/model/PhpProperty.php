<?php
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
use gossi\codegen\model\parts\ValuePart;
use gossi\docblock\Docblock;
use gossi\docblock\tags\VarTag;

/**
 * Represents a PHP property.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 * @author Thomas Gossmann
 */
class PhpProperty extends AbstractPhpMember {

	use TypeDocblockGeneratorPart;
	use ValuePart;

	/**
	 * Creates a new PHP property
	 *
	 * @param string $name the properties name
	 * @return static
	 */
	public static function create($name) {
		return new static($name);
	}


	/**
	 * Export array as php 5.4
	 *
	 * @param $var
	 * @param string $indent
	 * @return string
	 */
	static public function export($var, $indent="") {
		switch (gettype($var)) {
			case "string":
				return '"' . addcslashes($var, "\\\$\"\r\n\t\v\f") . '"';
			case "array":
				$indexed = array_keys($var) === range(0, count($var) - 1);
				$r = [];
				foreach ($var as $key => $value) {
					$r[] = "$indent    "
						. ($indexed ? "" : static::export($key) . " => ")
						. static::export($value, "$indent    ");
				}
				return "[\n" . implode(",\n", $r) . "\n" . $indent . "]";
			case "boolean":
				return $var ? "TRUE" : "FALSE";
			default:
				return var_export($var, TRUE);
		}
	}

	/**
	 * Creates a new PHP property from reflection
	 *
	 * @param \ReflectionProperty $ref
	 * @return static
	 */
	public static function fromReflection(\ReflectionProperty $ref) {
		$property = new static($ref->name);
		$property->setStatic($ref->isStatic())
			->setVisibility($ref->isPublic() ? self::VISIBILITY_PUBLIC : ($ref->isProtected() ? self::VISIBILITY_PROTECTED : self::VISIBILITY_PRIVATE));

		$docblock = new Docblock($ref);
		$property->setDocblock($docblock);
		$property->setDescription($docblock->getShortDescription());
		$property->setLongDescription($docblock->getLongDescription());

		$vars = $docblock->getTags('var');
		if ($vars->size() > 0) {
			$var = $vars->get(0);
			$property->setType($var->getType(), $var->getDescription());
		}

		$defaultProperties = $ref->getDeclaringClass()->getDefaultProperties();

		if (isset($defaultProperties[$ref->name])) {
			$default = $defaultProperties[$ref->name];
			if (is_string($default)) {
				$property->setValue($default);
			} elseif(is_array($default)) {
				$property->setValue(static::export($default));
			} elseif(is_object($default)) {
			} else {
				$property->setExpression($default);
			}
		}

		return $property;
	}

	/**
	 * Generates docblock based on provided information
	 */
	public function generateDocblock() {
		$docblock = $this->getDocblock();
		$docblock->setShortDescription($this->getDescription());
		$docblock->setLongDescription($this->getLongDescription());

		// var tag
		$this->generateTypeTag(new VarTag());
	}
}
