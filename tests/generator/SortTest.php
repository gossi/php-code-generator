<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\tests\generator;

use gossi\codegen\generator\comparator\DefaultConstantComparator;
use gossi\codegen\generator\comparator\DefaultMethodComparator;
use gossi\codegen\generator\comparator\DefaultPropertyComparator;
use gossi\codegen\generator\comparator\DefaultUseStatementComparator;
use gossi\codegen\model\PhpConstant;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpProperty;
use phootwork\collection\ArrayList;
use PHPUnit\Framework\TestCase;

/**
 * @group generator
 */
class SortTest extends TestCase {
	public function testDefaultUseStatementComparator(): void {
		$list = new ArrayList();

		$list->add('phootwork\lang\Comparable');
		$list->add('phootwork\lang\Text');
		$list->add('Doctrine\Instantiator\Instantiator');
		$list->add('phootwork\collection\ArrayList');
		$list->add('Symfony\Component\Finder\Finder');
		$list->add('phootwork\file\Path');
		$list->add('gossi\docblock\Docblock');
		$list->add('phootwork\lang\Text');
		$list->add('phootwork\tokenizer\PhpTokenizer');
		$list->add('phootwork\collection\Map');

		$list->sort(new DefaultUseStatementComparator());
		$this->assertEquals([
			'gossi\docblock\Docblock',
			'phootwork\collection\ArrayList',
			'phootwork\collection\Map',
			'phootwork\file\Path',
			'phootwork\lang\Comparable',
			'phootwork\lang\Text',
			'phootwork\lang\Text',
			'phootwork\tokenizer\PhpTokenizer',
			'Doctrine\Instantiator\Instantiator',
			'Symfony\Component\Finder\Finder',
		], $list->toArray());
	}

	public function testDefaultConstantComparator(): void {
		$list = new ArrayList();

		$list->add(new PhpConstant('FOO'));
		$list->add(new PhpConstant('bar'));
		$list->add(new PhpConstant('BAR'));
		$list->add(new PhpConstant('baz'));
		$list->add(new PhpConstant('BAZ'));

		$list->sort(new DefaultConstantComparator());
		$ordered = $list->map(function ($item) {
			return $item->getName();
		})->toArray();

		$this->assertEquals([
			'bar',
			'baz',
			'BAR',
			'BAZ',
			'FOO'
		], $ordered);
	}

	public function testDefaultMethodComparator(): void {
		$list = new ArrayList();

		$list->add(PhpMethod::create('moop')->setStatic(true));
		$list->add(PhpMethod::create('arr')->setVisibility(PhpMethod::VISIBILITY_PRIVATE));
		$list->add(PhpMethod::create('bar')->setVisibility(PhpMethod::VISIBILITY_PROTECTED));
		$list->add(PhpMethod::create('foo'));
		$list->add(PhpMethod::create('baz'));

		$list->sort(new DefaultMethodComparator());
		$ordered = $list->map(function ($item) {
			return $item->getName();
		})->toArray();

		$this->assertEquals([
			'moop', 'baz', 'foo', 'bar', 'arr'
		], $ordered);
	}

	public function testDefaultPropertyComparator(): void {
		$list = new ArrayList();

		$list->add(PhpProperty::create('arr')->setVisibility(PhpProperty::VISIBILITY_PRIVATE));
		$list->add(PhpProperty::create('bar')->setVisibility(PhpProperty::VISIBILITY_PROTECTED));
		$list->add(PhpProperty::create('foo'));
		$list->add(PhpProperty::create('baz'));

		$list->sort(new DefaultPropertyComparator());
		$ordered = $list->map(function ($item) {
			return $item->getName();
		})->toArray();

		$this->assertEquals([
			'baz', 'foo', 'bar', 'arr'
		], $ordered);
	}
}
