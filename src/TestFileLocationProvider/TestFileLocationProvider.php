<?php declare(strict_types=1);

namespace HJerichen\TestFileGenerator\TestFileLocationProvider;

use ReflectionClass;

interface TestFileLocationProvider
{
    public function getTestFileLocationForClass(ReflectionClass $class): string;
}
