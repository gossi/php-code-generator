<?php

namespace gossi\codegen\tests\fixture;

abstract class OverridableReflectionTest
{
    public function a()
    {
    }

    final public function b()
    {
    }

    public static function c()
    {
    }

    abstract public function d();

    protected function e()
    {
    }

    final protected function f()
    {
    }

    protected static function g()
    {
    }

    abstract protected function h();

    private function i()
    {
    }
}
