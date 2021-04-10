<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\generator;

use gossi\codegen\model\GenerateableInterface;
use phootwork\file\File;

/**
 * Code file generator.
 *
 * Generates code for a model and puts it into a file with `<?php` statements. Can also
 * generate header comments.
 *
 * @author Thomas Gossmann
 */
class CodeFileGenerator extends CodeGenerator {
	/**
	 * {@inheritDoc}
	 */
	public function generate(GenerateableInterface $model): string {
		$content = parent::generate($model);

		return $this->twig->render('file.twig', ['content' => $content, 'config' => $this->config]);
	}
}
