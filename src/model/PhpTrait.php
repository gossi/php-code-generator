<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\model;

use gossi\codegen\model\parts\PropertiesPart;
use gossi\codegen\model\parts\TraitsPart;
use gossi\codegen\parser\FileParser;
use gossi\codegen\parser\visitor\ConstantParserVisitor;
use gossi\codegen\parser\visitor\MethodParserVisitor;
use gossi\codegen\parser\visitor\PropertyParserVisitor;
use gossi\codegen\parser\visitor\TraitParserVisitor;
use phootwork\file\exception\FileNotFoundException;

/**
 * Represents a PHP trait.
 *
 * @author Thomas Gossmann
 */
class PhpTrait extends AbstractPhpStruct implements GenerateableInterface, TraitsInterface, PropertiesInterface {
	use PropertiesPart;
	use TraitsPart;

	/**
	 * Creates a PHP trait from a file
	 *
	 * @param string $filename
	 *
	 * @throws FileNotFoundException
	 *
	 * @return PhpTrait
	 *
	 */
	public static function fromFile(string $filename): self {
		$trait = new self();
		$parser = new FileParser($filename);
		$parser->addVisitor(new TraitParserVisitor($trait));
		$parser->addVisitor(new MethodParserVisitor($trait));
		$parser->addVisitor(new ConstantParserVisitor($trait));
		$parser->addVisitor(new PropertyParserVisitor($trait));
		$parser->parse();

		return $trait;
	}

	/**
	 * Creates a new PHP trait
	 *
	 * @param string $name qualified name
	 */
	public function __construct(string $name = '') {
		parent::__construct($name);
		$this->initProperties();
		$this->initTraits();
	}

	/**
	 * @inheritDoc
	 */
	public function generateDocblock(): void {
		parent::generateDocblock();
		$this->properties->each(fn (string $key, PhpProperty $prop) => $prop->generateDocblock());
	}
}
