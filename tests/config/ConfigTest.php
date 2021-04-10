<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\tests\config;

use gossi\codegen\config\CodeGeneratorConfig;
use gossi\codegen\generator\CodeGenerator;
use gossi\docblock\Docblock;
use phootwork\lang\ComparableComparator;
use phootwork\lang\Comparator;
use phootwork\lang\Text;
use PHPUnit\Framework\TestCase;

/**
 * @group config
 */
class ConfigTest extends TestCase {
	public function testDefaults(): void {
		$config = new CodeGeneratorConfig();

		$this->assertTrue($config->getGenerateDocblock());
		$this->assertTrue($config->getGenerateEmptyDocblock());
		$this->assertTrue($config->getGenerateTypeHints());
		$this->assertTrue($config->getGenerateReturnTypeHints());
		$this->assertTrue($config->getGeneratePropertyTypes());
		$this->assertTrue($config->isSortingEnabled());
		$this->assertEquals('8.0', $config->getMinPhpVersion());
		$this->assertEquals(CodeGenerator::SORT_USESTATEMENTS_DEFAULT, $config->getUseStatementSorting());
		$this->assertEquals(CodeGenerator::SORT_CONSTANTS_DEFAULT, $config->getConstantSorting());
		$this->assertEquals(CodeGenerator::SORT_PROPERTIES_DEFAULT, $config->getPropertySorting());
		$this->assertEquals(CodeGenerator::SORT_METHODS_DEFAULT, $config->getMethodSorting());
		$this->assertTrue($config->getHeaderComment()->isEmpty());
		$this->assertTrue($config->getHeaderDocblock()->isEmpty());
		$this->assertTrue($config->getDeclareStrictTypes());
		$this->assertTrue($config->getGenerateReturnTypeHints());
		$this->assertEquals('default', $config->getCodeStyle());
		$this->assertEquals([realpath(__DIR__ . '/../../resources/templates/default')], $config->getTemplatesDirs());
	}

	public function testDisableDocblock(): void {
		$config = new CodeGeneratorConfig(['generateDocblock' => false]);

		$this->assertFalse($config->getGenerateDocblock());
		$this->assertFalse($config->getGenerateEmptyDocblock());
	}

	public function testSetters(): void {
		$config = new CodeGeneratorConfig();

		$config->setGenerateDocblock(false);
		$this->assertFalse($config->getGenerateDocblock());
		$this->assertFalse($config->getGenerateEmptyDocblock());

		$config->setGenerateEmptyDocblock(true);
		$this->assertTrue($config->getGenerateDocblock());
		$this->assertTrue($config->getGenerateEmptyDocblock());

		$config->setGenerateEmptyDocblock(false);
		$this->assertTrue($config->getGenerateDocblock());
		$this->assertFalse($config->getGenerateEmptyDocblock());

		$config->setGenerateReturnTypeHints(true);
		$this->assertTrue($config->getGenerateReturnTypeHints());

		$config->setGenerateTypeHints(true);
		$this->assertTrue($config->getGenerateTypeHints());

		$config->setUseStatementSorting(false);
		$this->assertFalse($config->getUseStatementSorting());

		$config->setConstantSorting('abc');
		$this->assertEquals('abc', $config->getConstantSorting());

		$config->setPropertySorting(new ComparableComparator());
		$this->assertTrue($config->getPropertySorting() instanceof Comparator);

		$cmp = function ($a, $b) {
			return strcmp($a, $b);
		};
		$config->setMethodSorting($cmp);
		$this->assertSame($cmp, $config->getMethodSorting());

		$config->setSortingEnabled(false);
		$this->assertFalse($config->isSortingEnabled());

		$config->setFormattingEnabled(true);
		$this->assertTrue($config->isFormattingEnabled());

		$config->setMinPhpVersion('7.4');
		$this->assertEquals('7.4', $config->getMinPhpVersion());

		$config->setGeneratePropertyTypes(false);
		$this->assertFalse($config->getGeneratePropertyTypes());
	}

	public function testSetHeaderComment(): void {
		$config = new CodeGeneratorConfig();

		$config->setHeaderComment('String header comment.');
		$this->assertInstanceOf(Docblock::class, $config->getHeaderComment());
		$this->assertEquals("/**\n * String header comment.\n */", $config->getHeaderComment()->toString());

		$config->setHeaderComment(new Text('Stringable header comment.'));
		$this->assertInstanceOf(Docblock::class, $config->getHeaderComment());
		$this->assertEquals("/**\n * Stringable header comment.\n */", $config->getHeaderComment()->toString());

		$config->setHeaderComment(new Docblock('Docblock header comment.'));
		$this->assertInstanceOf(Docblock::class, $config->getHeaderComment());
		$this->assertEquals("/**\n * Docblock header comment.\n */", $config->getHeaderComment()->toString());
	}

	public function testSetHeaderDocblock(): void {
		$config = new CodeGeneratorConfig();

		$config->setHeaderDocblock('String header docblock.');
		$this->assertInstanceOf(Docblock::class, $config->getHeaderDocblock());
		$this->assertEquals("/**\n * String header docblock.\n */", $config->getHeaderDocblock()->toString());

		$config->setHeaderDocblock(new Text('Stringable header docblock.'));
		$this->assertInstanceOf(Docblock::class, $config->getHeaderDocblock());
		$this->assertEquals("/**\n * Stringable header docblock.\n */", $config->getHeaderDocblock()->toString());

		$config->setHeaderDocblock(new Docblock('Docblock header docblock.'));
		$this->assertInstanceOf(Docblock::class, $config->getHeaderDocblock());
		$this->assertEquals("/**\n * Docblock header docblock.\n */", $config->getHeaderDocblock()->toString());
	}

	public function testCodeStyle(): void {
		$config = new CodeGeneratorConfig();

		$config->setCodeStyle('PSR-12');
		$this->assertEquals('psr-12', $config->getCodeStyle());

		$config->setCodeStyle('unknow');
		$this->assertEquals('psr-12', $config->getCodeStyle());
	}

	public function testAddTemplatesDirs(): void {
		$config = new CodeGeneratorConfig();
		$expected = [
			sys_get_temp_dir(),
			realpath(__DIR__ . '/../../resources/templates/default')
		];

		$config->addTemplatesDirs(sys_get_temp_dir(), '/fake/dir');
		$this->assertEquals($expected, $config->getTemplatesDirs());
	}

	public function testDeclareStrictTypes(): void {
		$config = new CodeGeneratorConfig(['declareStrictTypes' => false]);

		$this->assertFalse($config->getDeclareStrictTypes());
		$this->assertFalse($config->getGenerateReturnTypeHints());
		$this->assertFalse($config->getGenerateTypeHints());
		$this->assertFalse($config->getGeneratePropertyTypes());
	}

	public function testSetDeclareStrictTypes(): void {
		$config = new CodeGeneratorConfig();
		$this->assertTrue($config->getDeclareStrictTypes());

		$config->setDeclareStrictTypes(false);

		$this->assertFalse($config->getDeclareStrictTypes());
		$this->assertFalse($config->getGenerateReturnTypeHints());
		$this->assertFalse($config->getGenerateTypeHints());
		$this->assertFalse($config->getGeneratePropertyTypes());
	}
}
