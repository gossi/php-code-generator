<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\parser\visitor\parts;

use gossi\codegen\model\PhpConstant;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use gossi\codegen\model\PhpProperty;
use gossi\docblock\Docblock;
use gossi\docblock\tags\ParamTag;
use gossi\docblock\tags\ReturnTag;
use gossi\docblock\tags\VarTag;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\UnionType;

trait TypeParserPart {
	public function parseType(
		PhpMethod|PhpProperty|PhpParameter $object, ClassMethod|Property|Param $node, Docblock $docblock
	): void {
		$nodeType = $node instanceof ClassMethod ? $node->returnType : $node->type;

		$type = match (true) {
			is_string($nodeType) => $nodeType,
			$nodeType === null => '',
			$nodeType instanceof Name, $nodeType instanceof Identifier => $this->getNameOrIdentifier($nodeType),
			$nodeType instanceof NullableType => $this->getNullableType($object, $nodeType),
			$nodeType instanceof UnionType => $this->getUnionType($nodeType->types)
		};

		$object->setType($type);

		$tag = match (true) {
			$object instanceof PhpMethod => $docblock->getTags('return')->get(0),
			$object instanceof PhpProperty => $docblock->getTags('var')->get(0),
			$object instanceof PhpParameter => $docblock->getTags('param')->find($object->getName(),
				fn (ParamTag $element, string $name): bool => $element->getVariable() === $name
			)
		};
		$this->setTypeFromTag($object, $tag);
	}

	private function getNullableType(PhpMethod|PhpProperty|PhpParameter $object, NullableType $type): string {
		$object->setNullable(true);

		return $this->getNameOrIdentifier($type->type);
	}

	private function getUnionType(array $types): string {
		return implode('|', array_map([$this, 'getNameOrIdentifier'], $types));
	}

	private function getNameOrIdentifier(Identifier|Name $type): string {
		return match (true) {
			$type instanceof Name => ($type->isFullyQualified() ? '\\' : '') . implode('\\', $type->parts),
			$type instanceof Identifier => $type->name,
		};
	}

	/**
	 * Set the type (if not previously inferred from the code) and the type description of the object,
	 * by reading it from the relative docblock tag.
	 *
	 * @param PhpMethod|PhpProperty|PhpParameter|PhpConstant $object
	 * @param ReturnTag|VarTag|ParamTag|null $tag
	 */
	private function setTypeFromTag(PhpMethod|PhpProperty|PhpParameter|PhpConstant $object, null|ReturnTag|VarTag|ParamTag $tag) {
		if ($tag !== null) {
			if ($object->getType() === '') {
				$object->setType($tag->getType());
			}
			$object->setTypeDescription($tag->getDescription());
		}
	}
}
