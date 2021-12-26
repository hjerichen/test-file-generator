<?php declare(strict_types=1);

namespace HJerichen\TestFileGenerator\Test\Unit\TestFileTemplateDataProvider;

use HJerichen\TestFileGenerator\TestFileGenerator;
use HJerichen\TestFileGenerator\TestFileLocationProvider\TestFileLocationProviderDefault;
use HJerichen\TestFileGenerator\TestFileTemplateDataProvider\DataProviderProphecies;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class DataProviderPropheciesTest extends TestCase
{
    private DataProviderProphecies $dataProvider;
    private array $data;

    protected function setUp(): void
    {
        parent::setUp();
        $this->data = [];

        $this->dataProvider = new DataProviderProphecies();
    }

    /* TESTS */

    public function testAppendDataForEmptyConstructor(): void
    {
        $class = new ReflectionClass(TestFileLocationProviderDefault::class);
        $this->dataProvider->setClass($class);
        $this->dataProvider->appendData($this->data);

        $expected = [
            'prophecyUses' => '',
            'prophecyProperties' => '',
            'prophecyInitialize' => '',
        ];
        $actual = $this->data;
        self::assertEquals($expected, $actual);
    }

    public function testAppendDataForServicesInConstructor(): void
    {
        $class = new ReflectionClass(TestFileGenerator::class);
        $this->dataProvider->setClass($class);
        $this->dataProvider->appendData($this->data);

        $prophecyUses = [
            'use HJerichen\TestFileGenerator\TestFileLocationProvider\TestFileLocationProvider;',
            'use HJerichen\TestFileGenerator\TestFileTemplateParser;',
            'use HJerichen\TestFileGenerator\TestFileTemplateDataProvider\DataProviderComposite;',
            'use HJerichen\TestFileGenerator\ClassForFileLoader;',
            'use HJerichen\TestFileGenerator\TestFileWriter\TestFileWriter;',
            'use Prophecy\Prophecy\ObjectProphecy;',
        ];
        $prophecyProperties = [
            'private ObjectProphecy $testFileLocationProvider;',
            'private ObjectProphecy $testFileTemplateParser;',
            'private ObjectProphecy $dataProvider;',
            'private ObjectProphecy $classForFileLoader;',
            'private ObjectProphecy $testFileWriter;',
        ];
        $prophecyInitialize = [
            '$this->testFileLocationProvider = $this->prophesize(TestFileLocationProvider::class);',
            '$this->testFileTemplateParser = $this->prophesize(TestFileTemplateParser::class);',
            '$this->dataProvider = $this->prophesize(DataProviderComposite::class);',
            '$this->classForFileLoader = $this->prophesize(ClassForFileLoader::class);',
            '$this->testFileWriter = $this->prophesize(TestFileWriter::class);',
        ];
        $expected = [
            'prophecyUses' => implode(separator: "\n", array: $prophecyUses),
            'prophecyProperties' => implode(separator: "\n    ", array: $prophecyProperties),
            'prophecyInitialize' => implode(separator: "\n        ", array: $prophecyInitialize),
        ];
        $actual = $this->data;
        self::assertEquals($expected, $actual);
    }
}
