<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\tests\parser;

use gossi\codegen\model\PhpClass;
use gossi\codegen\parser\FileParser;
use gossi\codegen\parser\visitor\ClassParserVisitor;
use phootwork\file\exception\FileException;
use PHPUnit\Framework\TestCase;

/**
 * @group parser
 */
class FileParserTest extends TestCase {
	public function testVisitors(): void {
		$struct = new PhpClass();
		$visitor = new ClassParserVisitor($struct);
		$parser = new FileParser('dummy-file');
		$parser->addVisitor($visitor);
		$this->assertTrue($parser->hasVisitor($visitor));
		$parser->removeVisitor($visitor);
		$this->assertFalse($parser->hasVisitor($visitor));
	}

	public function testGetConstantThrowsExceptionWhenConstantDoesNotExist(): void {
		$this->expectException(FileException::class);
		$this->expectExceptionMessage('File does not exist: file-not-found');

		$parser = new FileParser('file-not-found');
		$parser->parse();
	}
}
