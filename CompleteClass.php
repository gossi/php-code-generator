<?php declare(strict_types=1);
/*
 * This is an header comment.
 */
/**
 * This is an header docblock.
 */

namespace gossi\codegen\tests\fixtures;

use SplFileInfo;

/**
 * Class containing almost every element (constants,  properties,methods with body,parameters, traits)
 */
class CompleteClass {

	use VeryDummyTrait;

	const PROJECT_PATH = '/my/project/path';

	/**
	 * @var \DirectoryIterator A collection of directories
	 */
	private \DirectoryIterator $dirs;

	/**
	 * Get a file
	 *
	 * @param string $name
	 * @return SplFileInfo|null
	 */
	public function getFile(string $name): ?SplFileInfo {
		foreach ($this->dirs as $file) {
			if ($file->getName() === $name) {
				return $file;
			}
		}

		return null;
	}
}
