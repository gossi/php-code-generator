<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\tests;

use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpConstant;
use gossi\codegen\model\PhpInterface;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use gossi\codegen\model\PhpProperty;
use gossi\codegen\model\PhpTrait;
use gossi\docblock\Docblock;
use gossi\docblock\tags\AuthorTag;
use gossi\docblock\tags\SinceTag;

class Fixtures {

	/**
	 * Creates the Fixture Class
	 * 
	 * @return PhpClass
	 */
	public static function createEntity(): PhpClass {
		$classDoc = new Docblock('/**
 * Doc Comment.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */');

		$propDoc = new Docblock('/**
 * @var int
 */');
		$class = new PhpClass();
		$class->setQualifiedName('gossi\codegen\tests\fixtures\Entity')
			->setAbstract(true)
			->setDocblock($classDoc)
			->setDescription($classDoc->getShortDescription())
			->setLongDescription($classDoc->getLongDescription())
			->setProperty(PhpProperty::create('id')
				->setVisibility('private')
				->setDocblock($propDoc)
				->setType('int')
				->setDescription($propDoc->getShortDescription()))
			->setProperty(PhpProperty::create('enabled')
				->setVisibility('private')
				->setValue(false));

		$methodDoc = new Docblock('/**
 * Another doc comment.
 *
 * @param $a
 * @param array $b
 * @param \stdClass $c
 * @param string $d
 * @param callable $e
 */');
		$method = PhpMethod::create('__construct')
			->setFinal(true)
			->addParameter(PhpParameter::create('a'))
			->addParameter(PhpParameter::create()
				->setName('b')
				->setType('array')
				->setPassedByReference(true))
			->addParameter(PhpParameter::create()
				->setName('c')
				->setType('\\stdClass'))
			->addParameter(PhpParameter::create()
				->setName('d')
				->setType('string')
				->setValue('foo'))
			->addParameter(PhpParameter::create()
				->setName('e')
				->setType('callable'))
				->setDocblock($methodDoc)
				->setDescription($methodDoc->getShortDescription())
				->setLongDescription($methodDoc->getLongDescription());

		$class->setMethod($method);
		$class->setMethod(PhpMethod::create('foo')->setAbstract(true)->setVisibility('protected'));
		$class->setMethod(PhpMethod::create('bar')->setStatic(true)->setVisibility('private'));

		return $class;
	}

	/**
	 * Create ClassWithConstants
	 * 
	 * @return PhpClass
	 */
	public static function createClassWithConstants(): PhpClass {
		return PhpClass::create('gossi\\codegen\\tests\\fixtures\\ClassWithConstants')
			->setConstant(PhpConstant::create('BAR')->setExpression('self::FOO'))
			->setConstant(PhpConstant::create('FOO', 'bar'))
			->setConstant(PhpConstant::create('NMBR', 300));
	}

	/**
	 * Creates ClassWithTraits
	 * 
	 * @return PhpClass
	 */
	public static function createClassWithTraits(): PhpClass {
		return PhpClass::create('gossi\\codegen\\tests\\fixtures\\ClassWithTraits')
			->addUseStatement('gossi\\codegen\\tests\\fixtures\\DummyTrait', 'DT')
			->addTrait('DT');
	}

	/**
	 *
	 * @return PhpClass
	 */
	public static function createABClass(): PhpClass {
		return PhpClass::create()
			->setName('ABClass')
			->setMethod(PhpMethod::create('a'))
			->setMethod(PhpMethod::create('b'))
			->setProperty(PhpProperty::create('a'))
			->setProperty(PhpProperty::create('b'))
			->setConstant('a', 'foo')
			->setConstant('b', 'bar');
	}

	/**
	 * Creates ClassWithComments
	 * 
	 * @return PhpClass
	 */
	public static function createClassWithComments(): PhpClass {
		$class = PhpClass::create('gossi\\codegen\\tests\\fixtures\\ClassWithComments');
		$class->setDescription('A class with comments');
		$class->setLongDescription('Here is a super dooper long-description');
		$docblock = $class->getDocblock();
		$docblock->appendTag(AuthorTag::create('gossi'));
		$docblock->appendTag(SinceTag::create('0.2'));

		$class->setConstant(PhpConstant::create('FOO', 'bar')
			->setDescription('Best const ever')
			->setLongDescription('Aaaand we go along long')
			->setType('string')
			->setTypeDescription('baz')
		);

		$class->setProperty(PhpProperty::create('propper')
			->setDescription('best prop ever')
			->setLongDescription('Aaaand we go along long long')
			->setType('string')
			->setTypeDescription('Wer macht sauber?')
		);

		$class->setMethod(PhpMethod::create('setup')
			->setDescription('Short desc')
			->setLongDescription('Looong desc')
			->addParameter(PhpParameter::create('moo')
				->setType('boolean')
				->setTypeDescription('makes a cow'))
			->addParameter(PhpParameter::create('foo')
				->setType('foo', 'makes a fow'))
			->setType('boolean', 'true on success and false if it fails')
		);

		return $class;
	}

	/**
	 * Creates DummyInterface
	 * 
	 * @return PhpInterface
	 */
	public static function createDummyInterface(): PhpInterface {
		$interface = PhpInterface::create('DummyInterface')
			->setNamespace('gossi\codegen\tests\fixtures')
			->setDescription('Dummy docblock')
			->setMethod(PhpMethod::create('foo'));
		$interface->generateDocblock();

		return $interface;
	}

	/**
	 * Creates DummyTrait
	 * 
	 * @return PhpTrait
	 */
	public static function createDummyTrait(): PhpTrait {
		$trait = PhpTrait::create('DummyTrait')
			->setNamespace('gossi\\codegen\\tests\\fixtures')
			->setDescription('Dummy docblock')
			->setMethod(PhpMethod::create('foo')->setVisibility('public'))
			->setProperty(PhpProperty::create('iAmHidden')->setVisibility('private'))
			->addTrait('VeryDummyTrait');
		$trait->generateDocblock();

		return $trait;
	}
}
