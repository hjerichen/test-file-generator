<?php declare(strict_types=1);

namespace HJerichen\TestFileGenerator\Test\Unit;

use HJerichen\TestFileGenerator\ClassForFileLoader;
use HJerichen\TestFileGenerator\TestFileGenerator;
use HJerichen\TestFileGenerator\TestFileLocationProvider\TestFileLocationProvider;
use HJerichen\TestFileGenerator\TestFileTemplateDataProvider\DataProviderComposite;
use HJerichen\TestFileGenerator\TestFileTemplateDataProvider\DataProviderNamespace;
use HJerichen\TestFileGenerator\TestFileTemplateParser;
use HJerichen\TestFileGenerator\TestFileWriter\TestFileWriter;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use ReflectionClass;

class TestFileGeneratorTest extends TestCase
{
    use ProphecyTrait;

    private TestFileGenerator $testFileGenerator;

    private ObjectProphecy $testFileLocationProvider;
    private ObjectProphecy $testFileTemplateParser;
    private ObjectProphecy $classForFileLoader;
    private ObjectProphecy $testFileWriter;
    private ObjectProphecy $dataProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testFileLocationProvider = $this->prophesize(TestFileLocationProvider::class);
        $this->testFileTemplateParser = $this->prophesize(TestFileTemplateParser::class);
        $this->classForFileLoader = $this->prophesize(ClassForFileLoader::class);
        $this->testFileWriter = $this->prophesize(TestFileWriter::class);
        $this->dataProvider = $this->prophesize(DataProviderComposite::class);

        $this->testFileGenerator = new TestFileGenerator(
            $this->testFileLocationProvider->reveal(),
            $this->testFileTemplateParser->reveal(),
            $this->dataProvider->reveal(),
            $this->classForFileLoader->reveal(),
            $this->testFileWriter->reveal(),
        );
    }

    /* TESTS */

    public function testAppendTemplateDataProvider(): void
    {
        $dataProvider = new DataProviderComposite();
        $dataProvider1 = new DataProviderNamespace();

        $this->testFileGenerator = new TestFileGenerator(dataProvider: $dataProvider);
        $this->testFileGenerator->appendTemplateDataProvider($dataProvider1);

        $expected = $dataProvider1;
        $actual = $dataProvider[0];
        self::assertSame($expected, $actual);
    }

    public function testExecute(): void
    {
        $classFile = '/to/Class.php';
        $class = new ReflectionClass($this);
        $data = [];

        $this->classForFileLoader->loadClass($classFile)->willReturn($class);
        $this->testFileLocationProvider->getTestFileLocationForClass($class)->willReturn('/to/testFile.php');

        $this->dataProvider->setClass($class)->shouldBeCalledOnce();
        $this->dataProvider->appendData($data)->shouldBeCalledOnce();
        $this->testFileTemplateParser->execute($data)->shouldBeCalledOnce()->willReturn('content');
        $this->testFileWriter->writeTestFile('/to/testFile.php', 'content')->shouldBeCalledOnce();

        $this->testFileGenerator->execute($classFile);
    }
}
