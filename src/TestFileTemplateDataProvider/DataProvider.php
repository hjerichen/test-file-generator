<?php declare(strict_types=1);

namespace HJerichen\TestFileGenerator\TestFileTemplateDataProvider;

use ReflectionClass;

interface DataProvider
{
    public function setClass(ReflectionClass $class): void;

    public function appendData(array &$data): void;
}
