<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\tests\model;

use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpConstant;
use gossi\codegen\model\PhpInterface;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use gossi\codegen\model\PhpProperty;
use gossi\codegen\model\PhpTrait;
use gossi\docblock\Docblock;
use gossi\docblock\tags\AuthorTag;
use gossi\docblock\tags\SeeTag;
use gossi\docblock\tags\ThrowsTag;
use PHPUnit\Framework\TestCase;

/**
 * @group model
 */
class DocblockTest extends TestCase {
	const METHOD = 'myMethod';

	const PROP = 'myProperty';

	const CONSTANT = 'MY_CONSTANT';

	public function testClass(): void {
		$class = new PhpClass();
		$class->setName('class-name')
			->setDescription('this is my class')
			->setLongDescription('this is my very long class')
			->setProperty($this->getProperty())
			->setMethod($this->getMethod())
			->setConstant($this->getConstant())
			->generateDocblock();

		$this->assertFalse($class->getDocblock()->isEmpty());
		$this->assertNotNull($class->getProperty(self::PROP)->getDocblock());
		$this->assertNotNull($class->getMethod(self::METHOD)->getDocblock());
		$this->assertNotNull($class->getConstant(self::CONSTANT)->getDocblock());

		$docblock = $class->getDocblock();
		$author = AuthorTag::create()->setName('gossi')->setEmail('iiih@mail.me');
		$docblock->appendTag($author);

		$this->assertTrue($docblock->hasTag('author'));

		$expected = '/**
 * this is my class
 *
 * this is my very long class
 *
 * @author gossi <iiih@mail.me>
 */';
		$this->assertEquals($expected, $docblock->toString());
	}

	public function testEmptyClass(): void {
		$class = new PhpClass();
		$class->generateDocblock();
		$this->assertTrue($class->getDocblock()->isEmpty());
	}

	public function testInterface(): void {
		$interface = new PhpInterface();
		$interface->setDescription('my interface')->setLongDescription('this is my very long description')->setConstant($this->getConstant())->setMethod($this->getMethod());
		$interface->generateDocblock();

		$this->assertFalse($interface->getDocblock()->isEmpty());
		$this->assertNotNull($interface->getMethod(self::METHOD)->getDocblock());
		$this->assertNotNull($interface->getConstant(self::CONSTANT)->getDocblock());
	}

	public function testEmptyInterface(): void {
		$interface = new PhpInterface();
		$interface->generateDocblock();
		$this->assertTrue($interface->getDocblock()->isEmpty());
	}

	public function testTrait(): void {
		$trait = new PhpTrait();
		$trait->setDescription('my trait')->setLongDescription('this is my very long description')->setProperty($this->getProperty())->setMethod($this->getMethod());
		$trait->generateDocblock();

		$this->assertFalse($trait->getDocblock()->isEmpty());
		$this->assertNotNull($trait->getProperty(self::PROP)->getDocblock());
		$this->assertNotNull($trait->getMethod(self::METHOD)->getDocblock());
	}

	public function testEmptyTrait(): void {
		$trait = new PhpTrait();
		$trait->generateDocblock();
		$this->assertTrue($trait->getDocblock()->isEmpty());
	}

	public function testConstant(): void {
		$expected = '/**
 * my constant
 *
 * my very long contstant
 *
 * @var bool this constant is a boolean
 */';
		$constant = $this->getConstant();
		$constant->generateDocblock();

		$this->assertEquals($expected, '' . $constant->getDocblock()->toString());
	}

	public function testEmptyConstant(): void {
		$constant = new PhpConstant();
		$constant->generateDocblock();
		$this->assertTrue($constant->getDocblock()->isEmpty());
	}

	public function testProperty(): void {
		$expected = '/**
 * my prop
 *
 * my very long prop
 *
 * @var int this prop is an integer
 */';
		$property = $this->getProperty();
		$property->generateDocblock();

		$this->assertEquals($expected, $property->getDocblock()->toString());
	}

	public function testEmptyProperty(): void {
		$property = new PhpProperty(self::PROP);
		$property->generateDocblock();
		$this->assertTrue($property->getDocblock()->isEmpty());
	}

	public function testMethod(): void {
		$expected = '/**
 * my method
 *
 * my very long method
 *
 * @see MyClass#myMethod see-desc
 * @param $a method-param
 * @throws \Exception when something goes wrong
 * @return string this method returns a string
 */';
		$throws = new ThrowsTag('\Exception when something goes wrong');
		$doc = new Docblock();
		$doc->appendTag($throws);

		$method = $this->getMethod();
		$method->setDocblock($doc);
		$method->generateDocblock();
		$docblock = $method->getDocblock();

		$see = new SeeTag('MyClass#myMethod see-desc');
		$docblock->appendTag($see);

		$this->assertSame($docblock, $doc);
		$this->assertEquals($expected, $docblock->toString());
	}

	public function testEmptyMethod(): void {
		$method = new PhpMethod(self::METHOD);
		$method->generateDocblock();
		$this->assertTrue($method->getDocblock()->isEmpty());
	}

	public function testEmptyDocblock(): void {
		$docblock = new Docblock();
		$this->assertEquals("/**\n */", $docblock->toString());
	}

	public function testObjectParam(): void {
		$expected = '/**
 * @param Request $r
 * @param mixed $a
 * @return Response this method returns a response object
 */';
		$function = PhpMethod::create(self::METHOD)->setType('Response', 'this method returns a response object')->addParameter(PhpParameter::create('r')->setType('Request'))->addParameter(PhpParameter::create('a')->setType('mixed'));
		$function->generateDocblock();
		$this->assertSame($expected, $function->getDocblock()->toString());
	}

	private function getMethod(): PhpMethod {
		return PhpMethod::create(self::METHOD)
			->setDescription('my method')
			->setLongDescription('my very long method')
			->setType('string', 'this method returns a string')
			->addParameter(PhpParameter::create('a')->setDescription('method-param'));
	}

	private function getProperty(): PhpProperty {
		return PhpProperty::create(self::PROP)
			->setDescription('my prop')
			->setLongDescription('my very long prop')
			->setType('int', 'this prop is an integer');
	}

	private function getConstant(): PhpConstant {
		return PhpConstant::create(self::CONSTANT)
			->setDescription('my constant')
			->setLongDescription('my very long contstant')
			->setType('boolean', 'this constant is a boolean');
	}
}
