<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\model;

use gossi\codegen\model\parts\AbstractPart;
use gossi\codegen\model\parts\ConstantsPart;
use gossi\codegen\model\parts\FinalPart;
use gossi\codegen\model\parts\InterfacesPart;
use gossi\codegen\model\parts\PropertiesPart;
use gossi\codegen\model\parts\TraitsPart;
use gossi\codegen\parser\FileParser;
use gossi\codegen\parser\visitor\ClassParserVisitor;
use gossi\codegen\parser\visitor\ConstantParserVisitor;
use gossi\codegen\parser\visitor\MethodParserVisitor;
use gossi\codegen\parser\visitor\PropertyParserVisitor;
use phootwork\file\exception\FileException;

/**
 * Represents a PHP class.
 *
 * @author Thomas Gossmann
 */
class PhpClass extends AbstractPhpStruct implements GenerateableInterface, TraitsInterface, ConstantsInterface, PropertiesInterface {
	use AbstractPart;
	use ConstantsPart;
	use FinalPart;
	use InterfacesPart;
	use PropertiesPart;
	use TraitsPart;

	/** @var string */
	private string $parentClassName = '';

	/**
	 * Creates a PHP class from file
	 *
	 * @param string $filename
	 *
	 * @throws FileException
	 *
	 * @return PhpClass
	 */
	public static function fromFile(string $filename): self {
		$class = new self();
		$parser = new FileParser($filename);
		$parser->addVisitor(new ClassParserVisitor($class));
		$parser->addVisitor(new MethodParserVisitor($class));
		$parser->addVisitor(new ConstantParserVisitor($class));
		$parser->addVisitor(new PropertyParserVisitor($class));
		$parser->parse();

		return $class;
	}

	/**
	 * Creates a new PHP class
	 *
	 * @param string $name the qualified name
	 */
	public function __construct(string $name = '') {
		parent::__construct($name);
		$this->initProperties();
		$this->initConstants();
		$this->initInterfaces();
		$this->initTraits();
	}

	/**
	 * Returns the parent class name
	 *
	 * @return string
	 */
	public function getParentClassName(): string {
		return $this->parentClassName;
	}

	/**
	 * Sets the parent class name
	 *
	 * @param string $name the new parent
	 *
	 * @return $this
	 */
	public function setParentClassName(string $name): self {
		$this->parentClassName = $name;

		return $this;
	}

	public function hasParent(): bool {
		return $this->parentClassName !== '';
	}

	public function generateDocblock(): void {
		parent::generateDocblock();

		$this->constants->each(fn (string $key, PhpConstant $constant) => $constant->generateDocblock());
		$this->properties->each(fn (string $key, PhpProperty $property) => $property->generateDocblock());
	}
}
