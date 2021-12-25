<?php declare(strict_types=1);

namespace HJerichen\TestFileGenerator\TestFileTemplateDataProvider;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

class DataProviderTestCase implements DataProvider
{
    public function setClass(ReflectionClass $class): void
    {
    }

    public function appendData(array &$data): void
    {
        $data['testCaseClassNameShort'] = 'TestCase';
        $data['testCaseClassName'] = TestCase::class;
    }
}
