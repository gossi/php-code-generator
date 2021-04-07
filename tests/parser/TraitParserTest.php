<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 *  @license Apache-2.0
 */

namespace gossi\codegen\tests\parser;

use gossi\codegen\model\PhpTrait;
use gossi\codegen\tests\Fixtures;
use PHPUnit\Framework\TestCase;

/**
 * @group parser
 */
class TraitParserTest extends TestCase {
	public function setUp(): void {
		// they are not explicitely instantiated through new WhatEver(); and such not
		// required through composer's autoload
		require_once __DIR__ . '/../fixtures/DummyTrait.php';
	}

	public function testFromFile(): void {
		$expected = Fixtures::createDummyTrait();
		$actual = PhpTrait::fromFile(__DIR__ . '/../fixtures/DummyTrait.php');
		$this->assertEquals($expected, $actual);
	}
}
