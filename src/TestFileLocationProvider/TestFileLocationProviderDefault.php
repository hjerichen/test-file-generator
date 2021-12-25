<?php declare(strict_types=1);

namespace HJerichen\TestFileGenerator\TestFileLocationProvider;

use ReflectionClass;

class TestFileLocationProviderDefault implements TestFileLocationProvider
{

    public function getTestFileLocationForClass(ReflectionClass $class): string
    {
        return str_replace(
            search: ['/src', '.php'],
            replace: ['/tests/Unit', 'Test.php'],
            subject: $class->getFileName(),
        );
    }
}
