<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\model;

use gossi\codegen\model\parts\ConstantsPart;
use gossi\codegen\model\parts\InterfacesPart;
use gossi\codegen\parser\FileParser;
use gossi\codegen\parser\visitor\ConstantParserVisitor;
use gossi\codegen\parser\visitor\InterfaceParserVisitor;
use gossi\codegen\parser\visitor\MethodParserVisitor;
use phootwork\file\exception\FileNotFoundException;

/**
 * Represents a PHP interface.
 *
 * @author Thomas Gossmann
 */
class PhpInterface extends AbstractPhpStruct implements GenerateableInterface, ConstantsInterface {
	use ConstantsPart;
	use InterfacesPart;

	/**
	 * Creates a PHP interface from file
	 *
	 * @param string $filename
	 *
	 * @throws FileNotFoundException
	 *
	 * @return PhpInterface
	 *
	 */
	public static function fromFile(string $filename): self {
		$interface = new self();
		$parser = new FileParser($filename);
		$parser->addVisitor(new InterfaceParserVisitor($interface));
		$parser->addVisitor(new MethodParserVisitor($interface));
		$parser->addVisitor(new ConstantParserVisitor($interface));
		$parser->parse();

		return $interface;
	}

	/**
	 * Create a new PHP interface
	 *
	 * @param string $name qualified name
	 */
	public function __construct(string $name = '') {
		parent::__construct($name);
		$this->initConstants();
		$this->initInterfaces();
	}

	/**
	 * @inheritDoc
	 */
	public function generateDocblock(): void {
		parent::generateDocblock();
		$this->constants->each(fn (string $key, PhpConstant $constant) => $constant->generateDocblock());
	}
}
