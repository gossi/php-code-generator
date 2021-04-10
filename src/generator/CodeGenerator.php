<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\generator;

use gossi\codegen\config\CodeGeneratorConfig;
use gossi\codegen\model\AbstractPhpStruct;
use gossi\codegen\model\ConstantsInterface;
use gossi\codegen\model\GenerateableInterface;
use gossi\codegen\model\PropertiesInterface;
use gossi\codegen\utils\TwigExtension;
use phootwork\lang\Text;
use Susina\TwigExtensions\VariablesExtension;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

/**
 * Code generator
 *
 * Generates code for any generateable model
 *
 * @author Thomas Gossmann
 * @author Cristiano Cinotti
 */
class CodeGenerator {
	const SORT_USESTATEMENTS_DEFAULT = 'default';

	const SORT_CONSTANTS_DEFAULT = 'default';

	const SORT_PROPERTIES_DEFAULT = 'default';

	const SORT_METHODS_DEFAULT = 'default';

	protected CodeGeneratorConfig $config;
	protected Environment $twig;

	/**
	 *
	 * @param CodeGeneratorConfig|array $config
	 */
	public function __construct(CodeGeneratorConfig|array $config = []) {
		$this->config = is_array($config) ? new CodeGeneratorConfig($config) : $config;

		$this->twig = new Environment(new FilesystemLoader($this->config->getTemplatesDirs()), ['autoescape' => false]);
		$this->twig->addExtension(new VariablesExtension());
		$this->twig->addExtension(new TwigExtension($this->config));
	}

	/**
	 * @param GenerateableInterface $model
	 *
	 * @throws LoaderError
	 * @throws RuntimeError
	 * @throws SyntaxError
	 *
	 * @return string
	 *
	 */
	public function generate(GenerateableInterface $model): string {
		$this->sortModel($model);
		$template = new Text($model::class);
		$template = $template
			->substring($template->lastIndexOf('Php') + 3)
			->toLowerCase()
			->append('.twig')
			->toString()
		;

		return $this->twig->render($template, ['model' => $model, 'config' => $this->config]);
	}

	protected function sortModel(GenerateableInterface $model): void {
		if ($model instanceof AbstractPhpStruct) {
			$this->sortUseStatements($model);
			$this->sortMethods($model);
		}
		if ($model instanceof PropertiesInterface) {
			$this->sortProperties($model);
		}
		if ($model instanceof ConstantsInterface) {
			$this->sortConstants($model);
		}
	}

	protected function sortUseStatements(AbstractPhpStruct $model): void {
		if ($this->config->isSortingEnabled()
			&& ($useStatementSorting = $this->config->getUseStatementSorting()) !== false) {
			if (is_string($useStatementSorting)) {
				$useStatementSorting = ComparatorFactory::createUseStatementComparator($useStatementSorting);
			}
			$model->getUseStatements()->sort($useStatementSorting);
		}
	}

	protected function sortConstants(ConstantsInterface $model): void {
		if ($this->config->isSortingEnabled()
			&& ($constantSorting = $this->config->getConstantSorting()) !== false) {
			if (is_string($constantSorting)) {
				$constantSorting = ComparatorFactory::createConstantComparator($constantSorting);
			}
			$model->getConstants()->sort($constantSorting);
		}
	}

	protected function sortProperties(PropertiesInterface $model): void {
		if ($this->config->isSortingEnabled()
			&& ($propertySorting = $this->config->getPropertySorting()) !== false) {
			if (is_string($propertySorting)) {
				$propertySorting = ComparatorFactory::createPropertyComparator($propertySorting);
			}
			$model->getProperties()->sort($propertySorting);
		}
	}

	protected function sortMethods(AbstractPhpStruct $model): void {
		if ($this->config->isSortingEnabled()
			&& ($methodSorting = $this->config->getMethodSorting()) !== false) {
			if (is_string($methodSorting)) {
				$methodSorting = ComparatorFactory::createMethodComparator($methodSorting);
			}
			$model->getMethods()->sort($methodSorting);
		}
	}
}
