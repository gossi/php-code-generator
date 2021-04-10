<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\tests\parts;

trait TestUtils {
	private function getGeneratedContent($file): bool|string {
		return file_get_contents(__DIR__ . '/../generator/generated/' . $file);
	}

	private function getFixtureContent($file): bool|string {
		return file_get_contents(__DIR__ . '/../fixtures/' . $file);
	}
}
