<?php /** @noinspection PhpUnhandledExceptionInspection */
declare(strict_types=1);

namespace HJerichen\TestFileGenerator;

use HJerichen\TestFileGenerator\TestFileLocationProvider\TestFileLocationProvider;
use HJerichen\TestFileGenerator\TestFileLocationProvider\TestFileLocationProviderDefault;
use HJerichen\TestFileGenerator\TestFileTemplateDataProvider\DataProvider;
use HJerichen\TestFileGenerator\TestFileTemplateDataProvider\DataProviderClass;
use HJerichen\TestFileGenerator\TestFileTemplateDataProvider\DataProviderComposite;
use HJerichen\TestFileGenerator\TestFileTemplateDataProvider\DataProviderNamespace;
use HJerichen\TestFileGenerator\TestFileTemplateDataProvider\DataProviderProphecies;
use HJerichen\TestFileGenerator\TestFileTemplateDataProvider\DataProviderTestCase;
use HJerichen\TestFileGenerator\TestFileWriter\TestFileWriter;
use HJerichen\TestFileGenerator\TestFileWriter\TestFileWriterDefault;
use ReflectionClass;

class TestFileGenerator
{
    private TestFileLocationProvider $testFileLocationProvider;
    private TestFileTemplateParser $testFileTemplateParser;
    private DataProviderComposite $dataProvider;
    private ClassForFileLoader $classForFileLoader;
    private TestFileWriter $testFileWriter;

    /** @var ReflectionClass<object> */
    private ReflectionClass $class;
    private string $parsedTemplate;
    private array $data;

    public function __construct(
        TestFileLocationProvider $testFileLocationProvider = null,
        TestFileTemplateParser $testFileTemplateParser = null,
        DataProviderComposite $dataProvider = null,
        ClassForFileLoader $classForFileLoader = null,
        TestFileWriter $testFileWriter = null,
    ) {
        $this->testFileLocationProvider = $testFileLocationProvider ?? new TestFileLocationProviderDefault();
        $this->testFileTemplateParser = $testFileTemplateParser ?? new TestFileTemplateParser();
        $this->classForFileLoader = $classForFileLoader ?? new ClassForFileLoader();
        $this->testFileWriter = $testFileWriter ?? new TestFileWriterDefault();

        if ($dataProvider) {
            $this->dataProvider = $dataProvider;
        } else {
            $this->dataProvider = new DataProviderComposite();
            $this->dataProvider[] = new DataProviderClass();
            $this->dataProvider[] = new DataProviderTestCase();
            $this->dataProvider[] = new DataProviderNamespace();
            $this->dataProvider[] = new DataProviderProphecies();
        }
    }

    public function appendTemplateDataProvider(DataProvider $dataProvider): void
    {
        $this->dataProvider[] = $dataProvider;
    }

    public function execute(string $classFile): void
    {
        $this->loadClass($classFile);
        $this->generateDataForTemplate();
        $this->parseTemplate();
        $this->writeTestFile();
    }

    private function loadClass(string $classFile): void
    {
        $this->class = $this->classForFileLoader->loadClass($classFile);
    }

    private function generateDataForTemplate(): void
    {
        $this->data = [];
        $this->dataProvider->setClass($this->class);
        $this->dataProvider->appendData($this->data);
    }

    private function parseTemplate(): void
    {
        $this->parsedTemplate = $this->testFileTemplateParser->execute($this->data);
    }

    private function writeTestFile(): void
    {
        $target = $this->testFileLocationProvider->getTestFileLocationForClass($this->class);
        $this->testFileWriter->writeTestFile($target, $this->parsedTemplate);
    }
}
