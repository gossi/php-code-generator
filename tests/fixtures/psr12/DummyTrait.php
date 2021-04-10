<?php

declare(strict_types=1);

namespace gossi\codegen\tests\fixtures\psr12;

/**
 * Dummy docblock
 */
trait DummyTrait
{
    use VeryDummyTrait;

    /**
     * @var string
     */
    private string $iAmHidden;

    public function foo()
    {
    }
}
