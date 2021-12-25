<?php declare(strict_types=1);

namespace HJerichen\TestFileGenerator\Test\Unit\TestFileTemplateDataProvider;

use HJerichen\TestFileGenerator\TestFileTemplateDataProvider\DataProviderTestCase;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class DataProviderTestCaseTest extends TestCase
{
    private DataProviderTestCase $dataProviderTestCase;
    private array $data;

    protected function setUp(): void
    {
        parent::setUp();
        $class = new ReflectionClass($this);
        $this->data = [];

        $this->dataProviderTestCase = new DataProviderTestCase();
        $this->dataProviderTestCase->setClass($class);
    }

    /* TESTS */

    public function testAppendData(): void
    {
        $this->dataProviderTestCase->appendData($this->data);
        $this->assertDataIsEqualTo([
            'testCaseClassNameShort' => 'TestCase',
            'testCaseClassName' => TestCase::class,
        ]);
    }

    /* HELPERS */

    /** @noinspection PhpSameParameterValueInspection */
    private function assertDataIsEqualTo(array $expected): void
    {
        $actual = $this->data;
        self::assertEquals($expected, $actual);
    }
}
