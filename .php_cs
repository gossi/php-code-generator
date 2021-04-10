<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 *  @license Apache-2.0
 */

$config = new phootwork\fixer\Config();
$config->getFinder()
    ->exclude(['fixtures', 'generated'])
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

return $config;
