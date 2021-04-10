<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\config;

use gossi\codegen\generator\CodeGenerator;
use gossi\docblock\Docblock;
use phootwork\lang\Comparator;
use Stringable;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Configuration for code generation
 *
 * @author Thomas Gossmann
 */
class CodeGeneratorConfig {
	protected array $options;

	/**
	 * Creates a new configuration for code generator
	 *
	 * @see https://php-code-generator.readthedocs.org/en/latest/generator.html
	 *
	 * @param array $options
	 */
	public function __construct(array $options = []) {
		$resolver = new OptionsResolver();
		$this->configureOptions($resolver);
		$this->options = $resolver->resolve($options);
	}

	protected function configureOptions(OptionsResolver $resolver): void {
		$resolver->setDefaults([
			'generateDocblock' => true,
			'generateEmptyDocblock' => fn (Options $options) => $options['generateDocblock'],
			'generateTypeHints' => fn (Options $options) => $options['declareStrictTypes'],
			'generateReturnTypeHints' => fn (Options $options) => $options['declareStrictTypes'],
			'generatePropertyTypes' => fn (Options $options) => $options['declareStrictTypes'],
			'minPhpVersion' => '8.0',
			'enableSorting' => true,
			'useStatementSorting' => CodeGenerator::SORT_USESTATEMENTS_DEFAULT,
			'constantSorting' => CodeGenerator::SORT_CONSTANTS_DEFAULT,
			'propertySorting' => CodeGenerator::SORT_PROPERTIES_DEFAULT,
			'methodSorting' => CodeGenerator::SORT_METHODS_DEFAULT,
			'headerComment' => '',
			'headerDocblock' => '',
			'declareStrictTypes' => true,
			'codeStyle' => 'default',
			'templatesDirs' => []
		]);

		$resolver->setAllowedTypes('generateDocblock', 'bool');
		$resolver->setAllowedTypes('generateEmptyDocblock', 'bool');
		$resolver->setAllowedTypes('generateTypeHints', 'bool');
		$resolver->setAllowedTypes('generateReturnTypeHints', 'bool');
		$resolver->setAllowedTypes('generatePropertyTypes', 'bool');
		$resolver->setAllowedTypes('minPhpVersion', 'string');
		$resolver->setAllowedTypes('enableSorting', 'bool');
		$resolver->setAllowedTypes('useStatementSorting', ['bool', 'string', '\Closure', Comparator::class]);
		$resolver->setAllowedTypes('constantSorting', ['bool', 'string', '\Closure', Comparator::class]);
		$resolver->setAllowedTypes('propertySorting', ['bool', 'string', '\Closure', Comparator::class]);
		$resolver->setAllowedTypes('methodSorting', ['bool', 'string', '\Closure', Comparator::class]);
		$resolver->setAllowedTypes('headerComment', ['string', Docblock::class]);
		$resolver->setAllowedTypes('headerDocblock', ['string', Docblock::class]);
		$resolver->setAllowedTypes('declareStrictTypes', 'bool');
		$resolver->setAllowedTypes('codeStyle', 'string');
		$resolver->setAllowedTypes('templatesDirs', 'string[]');

		/** All templates directories must exists */
		$resolver->setAllowedValues('templatesDirs',
			fn (array $dirs): bool => count(array_filter($dirs, 'is_dir')) === count($dirs)
		);
		$resolver->setAllowedValues('codeStyle',
			fn (string $value): bool => in_array(strtolower($value), ['default', 'phootwork', 'psr-12'])
		);
		$resolver->setAllowedValues('minPhpVersion', fn (string $value): bool => in_array($value, ['7.4', '8.0']));

		$resolver->setNormalizer('headerComment',
			fn (Options $options, Docblock|string $value): Docblock => is_string($value) ? new Docblock(str_replace('/*', '/**', $value)) : $value
		);
		$resolver->setNormalizer('headerDocblock',
			fn (Options $options, Docblock|string $value): Docblock => is_string($value) ? new Docblock($value) : $value
		);

		$resolver->setNormalizer('codeStyle', function (Options $options, string $value): string {
			$value = strtolower($value);

			return $value === 'phootwork' ? 'default' : $value;
		}
		);

		$resolver->setNormalizer('templatesDirs', function (Options $options, array $value): array {
			$value[] = realpath(__DIR__ . '/../../resources/templates/' . $options['codeStyle']);

			return $value;
		}
		);
	}

	/**
	 * Returns whether docblocks should be generated
	 *
	 * @return bool `true` if they will be generated and `false` if not
	 */
	public function getGenerateDocblock(): bool {
		return $this->options['generateDocblock'];
	}

	/**
	 * Sets whether docblocks should be generated
	 *
	 * @param bool $generate `true` if they will be generated and `false` if not
	 *
	 * @return $this
	 */
	public function setGenerateDocblock(bool $generate): self {
		$this->options['generateDocblock'] = $generate;
		if (!$generate) {
			$this->options['generateEmptyDocblock'] = $generate;
		}

		return $this;
	}

	/**
	 * Returns whether empty docblocks are generated
	 *
	 * @return bool `true` if they will be generated and `false` if not
	 */
	public function getGenerateEmptyDocblock(): bool {
		return $this->options['generateEmptyDocblock'];
	}

	/**
	 * Sets whether empty docblocks are generated
	 *
	 * @param bool $generate `true` if they will be generated and `false` if not
	 *
	 * @return $this
	 */
	public function setGenerateEmptyDocblock(bool $generate): self {
		$this->options['generateEmptyDocblock'] = $generate;
		if ($generate) {
			$this->options['generateDocblock'] = $generate;
		}

		return $this;
	}

	/**
	 * Returns whether type hints will be generated for method parameters
	 *
	 * @return bool `true` if they will be generated and `false` if not
	 */
	public function getGenerateTypeHints(): bool {
		return $this->options['generateTypeHints'];
	}

	/**
	 * Returns whether sorting is enabled
	 *
	 * @return bool `true` if it is enabled and `false` if not
	 */
	public function isSortingEnabled(): bool {
		return $this->options['enableSorting'];
	}

	/**
	 * Returns whether formatting is enalbed
	 *
	 * @return bool `true` if it is enabled and `false` if not
	 */
	public function isFormattingEnabled(): bool {
		return $this->options['enableFormatting'];
	}

	/**
	 * Sets whether sorting is enabled
	 *
	 * @param $enabled bool `true` if it is enabled and `false` if not
	 *
	 * @return $this
	 */
	public function setSortingEnabled(bool $enabled): self {
		$this->options['enableSorting'] = $enabled;

		return $this;
	}

	/**
	 * Sets whether formatting is enabled
	 *
	 * @param $enabled bool `true` if it is enabled and `false` if not
	 *
	 * @return $this
	 */
	public function setFormattingEnabled(bool $enabled): self {
		$this->options['enableFormatting'] = $enabled;

		return $this;
	}

	/**
	 * Returns the use statement sorting
	 *
	 * @return string|bool|Comparator|\Closure
	 */
	public function getUseStatementSorting(): string|bool|Comparator|\Closure {
		return $this->options['useStatementSorting'];
	}

	/**
	 * Returns the constant sorting
	 *
	 * @return string|bool|Comparator|\Closure
	 */
	public function getConstantSorting(): string|bool|Comparator|\Closure {
		return $this->options['constantSorting'];
	}

	/**
	 * Returns the property sorting
	 *
	 * @return string|bool|Comparator|\Closure
	 */
	public function getPropertySorting(): string|bool|Comparator|\Closure {
		return $this->options['propertySorting'];
	}

	/**
	 * Returns the method sorting
	 *
	 * @return string|bool|Comparator|\Closure
	 */
	public function getMethodSorting(): string|bool|Comparator|\Closure {
		return $this->options['methodSorting'];
	}

	/**
	 * Sets whether scalar type hints will be generated for method parameters
	 *
	 * @param bool $generate `true` if they will be generated and `false` if not
	 *
	 * @return $this
	 */
	public function setGenerateTypeHints(bool $generate): self {
		$this->options['generateTypeHints'] = $generate;

		return $this;
	}

	/**
	 * Returns whether return type hints will be generated for method parameters
	 *
	 * @return bool `true` if they will be generated and `false` if not
	 */
	public function getGenerateReturnTypeHints(): bool {
		return $this->options['generateReturnTypeHints'];
	}

	/**
	 * Sets whether return type hints will be generated for method parameters
	 *
	 * @param bool $generate `true` if they will be generated and `false` if not
	 *
	 * @return $this
	 */
	public function setGenerateReturnTypeHints(bool $generate): self {
		$this->options['generateReturnTypeHints'] = $generate;

		return $this;
	}

	/**
	 * Returns the use statement sorting
	 *
	 * @param $sorting string|bool|Comparator|\Closure
	 *
	 * @return $this
	 */
	public function setUseStatementSorting(string|bool|Comparator|\Closure $sorting): self {
		$this->options['useStatementSorting'] = $sorting;

		return $this;
	}

	/**
	 * Returns the constant sorting
	 *
	 * @param $sorting string|bool|Comparator|\Closure
	 *
	 * @return $this
	 */
	public function setConstantSorting(string|bool|Comparator|\Closure $sorting): self {
		$this->options['constantSorting'] = $sorting;

		return $this;
	}

	/**
	 * Returns the property sorting
	 *
	 * @param $sorting string|bool|Comparator|\Closure
	 *
	 * @return $this
	 */
	public function setPropertySorting(string|bool|Comparator|\Closure $sorting): self {
		$this->options['propertySorting'] = $sorting;

		return $this;
	}

	/**
	 * Returns the method sorting
	 *
	 * @param $sorting string|bool|Comparator|\Closure
	 *
	 * @return $this
	 */
	public function setMethodSorting(string|bool|Comparator|\Closure $sorting): self {
		$this->options['methodSorting'] = $sorting;

		return $this;
	}

	/**
	 * Returns the file header comment
	 *
	 * @return Docblock|null the header comment
	 */
	public function getHeaderComment(): ?Docblock {
		return $this->options['headerComment'];
	}

	/**
	 * Sets the file header comment
	 *
	 * @param string|Stringable|Docblock $comment the header comment
	 *
	 * @return $this
	 */
	public function setHeaderComment(string|Stringable|Docblock $comment): self {
		$this->options['headerComment'] = $comment instanceof Docblock ? $comment :
			new Docblock((string) $comment);

		return $this;
	}

	/**
	 * Returns the file header docblock
	 *
	 * @return Docblock|null the docblock
	 */
	public function getHeaderDocblock(): ?Docblock {
		return $this->options['headerDocblock'];
	}

	/**
	 * Sets the file header docblock
	 *
	 * @param string|Stringable|Docblock $docblock the docblock
	 *
	 * @return $this
	 */
	public function setHeaderDocblock(string|Stringable|Docblock $docblock): self {
		$this->options['headerDocblock'] = $docblock instanceof Docblock ? $docblock :
			new Docblock((string) $docblock);

		return $this;
	}

	/**
	 * Returns whether a `declare(strict_types=1);` statement should be printed
	 * as the very first instruction
	 *
	 * @return bool `true` if it will be printed and `false` if not
	 */
	public function getDeclareStrictTypes(): bool {
		return $this->options['declareStrictTypes'];
	}

	/**
	 * Sets whether a `declare(strict_types=1);` statement should be printed
	 * below the header comments
	 *
	 * @param bool $strict `true` if it will be printed and `false` if not
	 *
	 * @return $this
	 */
	public function setDeclareStrictTypes(bool $strict): self {
		$this->options['declareStrictTypes'] = $strict;

		$this->options['generateReturnTypeHints'] = $strict;
		$this->options['generateTypeHints'] = $strict;
		$this->options['generatePropertyTypes'] = $strict;

		return $this;
	}

	public function getCodeStyle(): string {
		return $this->options['codeStyle'];
	}

	public function setCodeStyle(string $style): self {
		$style = strtolower($style);
		if (in_array($style, ['default', 'phootwork', 'psr-12'])) {
			$this->options['codeStyle'] = $style;
		}

		return $this;
	}

	/**
	 * @return string[]
	 */
	public function getTemplatesDirs(): array {
		return $this->options['templatesDirs'];
	}

	/**
	 * Prepend one or more directories, where templates reside.
	 * The order of the directories is mandatory to correctly find overridden templates.
	 *
	 * @param string ...$dirs
	 *
	 * @return $this
	 */
	public function addTemplatesDirs(string ...$dirs): self {
		$dirs = array_reverse($dirs);
		foreach ($dirs as $dir) {
			if (is_dir($dir)) {
				array_unshift($this->options['templatesDirs'], $dir);
			}
		}

		return $this;
	}

	public function getGeneratePropertyTypes(): bool {
		return $this->options['generatePropertyTypes'];
	}

	public function setGeneratePropertyTypes(bool $types): self {
		$this->options['generatePropertyTypes'] = $types;

		return $this;
	}

	public function getMinPhpVersion(): string {
		return $this->options['minPhpVersion'];
	}

	public function setMinPhpVersion(string $version): self {
		if (in_array($version, ['7.4', '8.0'])) {
			$this->options['minPhpVersion'] = $version;
		}

		return $this;
	}
}
