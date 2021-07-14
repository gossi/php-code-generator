<?php declare(strict_types=1);
/*
 * This is an header comment.
 */
/**
 * This is an header docblock.
 */

namespace gossi\codegen\tests\fixtures;

use phootwork\lang\Text;
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

	/**
	 * @param mixed $name
	 * @param string|\Stringable|Text $content
	 * @return bool
	 */
	protected function putFileContent(mixed $name, string|\Stringable|Text $content): bool {
		return file_put_contents($name, $content);
	}

	/**
	 * @param string $name
	 * @return string|\Stringable|Text
	 */
	private function getContent(string $name): string|\Stringable|Text {
	}
}
