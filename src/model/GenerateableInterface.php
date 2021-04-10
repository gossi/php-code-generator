<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\model;

/**
 * Represents all models that can be generated with a code generator
 *
 * @author Thomas Gossmann
 */
interface GenerateableInterface {

	/**
	 * Generates docblock based on provided information
	 * 
	 * @return void
	 */
	public function generateDocblock(): void;
}
