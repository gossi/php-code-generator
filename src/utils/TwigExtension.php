<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\utils;

use gossi\codegen\config\CodeGeneratorConfig;
use gossi\codegen\model\DocblockInterface;
use gossi\codegen\model\PhpConstant;
use gossi\codegen\model\PhpParameter;
use gossi\codegen\model\PhpProperty;
use gossi\codegen\model\RoutineInterface;
use gossi\codegen\model\ValueInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Twig extension containing useful tests, filters and functions to use into templates.
 *
 * @author Cristiano Cinotti
 */
class TwigExtension extends AbstractExtension {
	private CodeGeneratorConfig $config;

	public function __construct(CodeGeneratorConfig $config) {
		$this->config = $config;
	}

	public function getFilters(): array {
		return [

			new TwigFilter('commonName', fn (string $variable): string => str_contains($variable, '\\') ? substr($variable, strrpos($variable, '\\') + 1) : $variable),
			new TwigFilter('type', [$this, 'getType']),
			new TwigFilter('value', [$this, 'getValue']),
			new TwigFilter('repeat', 'str_repeat')
		];
	}

	public function getFunctions(): array {
		return [
			new TwigFunction('getDocblock', [$this, 'buildDocblock'])
		];
	}

	/**
	 * Render the type of the given model.
	 *
	 * @param PhpConstant|PhpParameter|PhpProperty|RoutineInterface $model
	 *
	 * @return string
	 */
	public function getType(PhpConstant|PhpParameter|PhpProperty|RoutineInterface $model): string {
		$type = $model->getType();
		$generateTypeHints = $model instanceof RoutineInterface ?
			$this->config->getGenerateReturnTypeHints() : $this->config->getGenerateTypeHints();

		if (!empty($type) && $generateTypeHints) {
			if (!str_contains($type, '|') && ($type !== 'mixed' || $this->config->getMinPhpVersion() === '8.0')) {
				return $model->getNullable() ? "?$type" : $type;
			}

			//If PHP8+ render union types
			if ($this->config->getMinPhpVersion() === '8.0') {
				return $type;
			}
		}

		return '';
	}

	/**
	 * Render the value of the given model.
	 *
	 * @param ValueInterface $model
	 *
	 * @return string
	 */
	public function getValue(ValueInterface $model): string {
		if ($model->isExpression()) {
			return $model->getExpression();
		}

		$value = $model->getValue();

		return match(true) {
			$value instanceof PhpConstant => $value->getName(),
			$value === null => 'null',
			default => var_export($value, true)
		};
	}

	/**
	 * Generate the docblock for the given model.
	 *
	 * @param DocblockInterface $model
	 *
	 * @return array The generated docblock array of lines
	 */
	public function buildDocblock(DocblockInterface $model): array {
		if ($this->config->getGenerateDocblock()) {
			$model->generateDocblock();
		}
		$docblock = $model->getDocblock();
		if (!$docblock->isEmpty() || $this->config->getGenerateEmptyDocblock()) {
			return explode("\n", $docblock->toString());
		}

		return [];
	}
}
