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
use gossi\codegen\model\PhpClass;
use gossi\codegen\tests\Fixtures;
use gossi\codegen\tests\parts\TestUtils;
use PHPUnit\Framework\TestCase;

/**
 * @group generator
 */
class ClassGeneratorTest extends TestCase {
	use TestUtils;

	public function testSignature(): void {
		$expected = "class MyClass {\n}\n";

		$class = PhpClass::create('MyClass');
		$generator = new CodeGenerator(['generateDocblock' => false]);
		$code = $generator->generate($class);

		$this->assertEquals($expected, $code);
	}

	public function testAbstract(): void {
		$expected = "abstract class MyClass {\n}\n";

		$class = PhpClass::create('MyClass')->setAbstract(true);
		$generator = new CodeGenerator(['generateDocblock' => false]);
		$code = $generator->generate($class);

		$this->assertEquals($expected, $code);
	}

	public function testFinal(): void {
		$expected = "final class MyClass {\n}\n";

		$class = PhpClass::create('MyClass')->setFinal(true);
		$generator = new CodeGenerator(['generateDocblock' => false]);
		$code = $generator->generate($class);

		$this->assertEquals($expected, $code);
	}

	public function testInterfaces(): void {
		$generator = new CodeGenerator(['generateDocblock' => false]);

		$expected = "class MyClass implements \Iterator {\n}\n";
		$class = PhpClass::create('MyClass')->addInterface('\Iterator');
		$this->assertEquals($expected, $generator->generate($class));

		$expected = "class MyClass implements \Iterator, \ArrayAccess {\n}\n";
		$class = PhpClass::create('MyClass')->addInterface('\Iterator')->addInterface('\ArrayAccess');
		$this->assertEquals($expected, $generator->generate($class));
	}

	public function testParent(): void {
		$expected = "class MyClass extends MyParent {\n}\n";

		$class = PhpClass::create('MyClass')->setParentClassName('MyParent');
		$generator = new CodeGenerator(['generateDocblock' => false]);
		$code = $generator->generate($class);

		$this->assertEquals($expected, $code);
	}

	public function testABClass(): void {
		$class = Fixtures::createABClass();

		$CodeGenerator = new CodeGenerator(['generateDocblock' => false]);
		$modelCode = $CodeGenerator->generate($class);
		$this->assertEquals($this->getGeneratedContent('ABClass.php'), $modelCode);
		$generator = new CodeGenerator(['generateDocblock' => false]);
		$code = $generator->generate($class);
		$this->assertEquals($modelCode, $code);

		$CodeGenerator = new CodeGenerator(['generateDocblock' => true]);
		$modelCode = $CodeGenerator->generate($class);
		$this->assertEquals($this->getGeneratedContent('ABClassWithComments.php'), $modelCode);
		$generator = new CodeGenerator(['generateDocblock' => true]);
		$code = $generator->generate($class);
		$this->assertEquals($modelCode, $code);
	}

	public function testRequireTraitsClass(): void {
		$class = PhpClass::create('RequireTraitsClass')
			->addRequiredFile('FooBar.php')
			->addRequiredFile('ABClass.php')
			->addTrait('Iterator');

		$generator = new CodeGenerator(['generateDocblock' => false]);
		$code = $generator->generate($class);
		$this->assertEquals($this->getGeneratedContent('RequireTraitsClass.php'), $code);
	}

	public function testMyCollection(): void {
		$class = PhpClass::fromFile(__DIR__ . '/../fixtures/MyCollection.php');

		$generator = new CodeFileGenerator(['generateDocblock' => false]);
		$code = $generator->generate($class);

		$this->assertEquals($this->getFixtureContent('MyCollection.php'), $code);
	}

	public function testUseStatements(): void {
		$class = new PhpClass('Foo\\Bar');
		$class->addUseStatement('Bam\\Baz');

		$codegen = new CodeFileGenerator(
			['generateDocblock' => false, 'generateEmptyDocblock' => false, 'declareStrictTypes' => false]
		);
		$code = $codegen->generate($class);

		$this->assertEquals($this->getGeneratedContent('FooBar.php'), $code);

		$class = new PhpClass('Foo\\Bar');
		$class->addUseStatement('Bam\\Baz', 'BamBaz');

		$codegen = new CodeFileGenerator(
			['generateDocblock' => false, 'generateEmptyDocblock' => false, 'declareStrictTypes' => false]
		);
		$code = $codegen->generate($class);

		$this->assertEquals($this->getGeneratedContent('FooBarWithAlias.php'), $code);

		$class = new PhpClass('Foo');
		$class->addUseStatement('Bar');

		$generator = new CodeGenerator(['generateDocblock' => false]);
		$code = $generator->generate($class);
		$expected = "class Foo {\n}\n";

		$this->assertEquals($expected, $code);
	}

	public function testMyCollection2(): void {
		$class = PhpClass::fromFile(__DIR__ . '/../fixtures/MyCollection2.php');

		$generator = new CodeFileGenerator(['generateDocblock' => false]);
		$code = $generator->generate($class);

		$this->assertEquals($this->getFixtureContent('MyCollection2.php'), $code);
	}

	public function testCompleteClass(): void {
		$class = PhpClass::fromFile(__DIR__ . '/../fixtures/CompleteClass.php');

		$generator = new CodeFileGenerator([
			'generateEmptyDocblock' => false,
			'headerComment' => 'This is an header comment.',
			'headerDocblock' => 'This is an header docblock.'
		]);
		$code = $generator->generate($class);

		$this->assertEquals($this->getFixtureContent('CompleteClass.php'), $code);
	}

	public function testPsr12Class(): void {
		$class = PhpClass::fromFile(__DIR__ . '/../fixtures/psr12/CompleteClass.php');

		$generator = new CodeFileGenerator([
			'generateEmptyDocblock' => false,
			'headerComment' => 'This is an header comment.',
			'headerDocblock' => 'This is an header docblock.',
			'codeStyle' => 'psr-12'
		]);
		$code = $generator->generate($class);

		$this->assertEquals($this->getFixtureContent('psr12/CompleteClass.php'), $code);
	}
}
