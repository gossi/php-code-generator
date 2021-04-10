<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\tests\generator\comparator;

use gossi\codegen\generator\comparator\DefaultPropertyComparator;
use gossi\codegen\model\PhpProperty;
use phootwork\lang\Text;
use PHPUnit\Framework\TestCase;

class PropertyComparatorTest extends TestCase {
	public function testThrowsExceptionWhenFirstParameterIsNotPhpProperty(): void {
		$this->expectException(\TypeError::class);
		$this->expectExceptionMessage('DefaultPropertyComparator::compare method compares PhpProperty objects only');

		$comparator = new DefaultPropertyComparator();
		$comparator->compare(new Text('text'), new PhpProperty('property2'));
	}

	public function testThrowsExceptionWhenSecondParameterIsNotPhpProperty(): void {
		$this->expectException(\TypeError::class);
		$this->expectExceptionMessage('DefaultPropertyComparator::compare method compares PhpProperty objects only');

		$comparator = new DefaultPropertyComparator();
		$comparator->compare(new PhpProperty('property1'), new Text('text'));
	}
}
