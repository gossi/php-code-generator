<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\tests\generator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\generator\CodeGenerator;
use gossi\codegen\model\PhpTrait;
use gossi\codegen\tests\parts\TestUtils;
use PHPUnit\Framework\TestCase;

/**
 * @group generator
 */
class TraitGeneratorTest extends TestCase {
	use TestUtils;

	public function testSignature(): void {
		$expected = "trait MyTrait {\n}\n";

		$trait = PhpTrait::create('MyTrait');
		$generator = new CodeGenerator(['generateEmptyDocblock' => false]);
		$code = $generator->generate($trait);

		$this->assertEquals($expected, $code);
	}

	public function testPsr12Trait(): void {
		$trait = PhpTrait::fromFile(__DIR__ . '/../fixtures/psr12/DummyTrait.php');

		$generator = new CodeFileGenerator([
			'codeStyle' => 'psr-12',
			'generateEmptyDocblock' => false
		]);
		$code = $generator->generate($trait);

		$this->assertEquals($this->getFixtureContent('psr12/DummyTrait.php'), $code);
	}
}
