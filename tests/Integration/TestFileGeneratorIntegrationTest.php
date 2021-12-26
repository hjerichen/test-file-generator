<?php /** @noinspection PhpUnhandledExceptionInspection */
declare(strict_types=1);

namespace HJerichen\TestFileGenerator\Test\Integration;

use HJerichen\ProphecyPHP\PHPProphetTrait;
use HJerichen\TestFileGenerator\TestFileGenerator;
use HJerichen\TestFileGenerator\TestFileLocationProvider\TestFileLocationProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Filesystem\Filesystem;

class TestFileGeneratorIntegrationTest extends TestCase
{
    use PHPProphetTrait;

    private TestFileGenerator $testFileGenerator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->preparePHPProphet();
        $this->createTempDirectory();

        $this->testFileGenerator = new TestFileGenerator(
            testFileLocationProvider: $this->getLocationProvider()
        );
    }

    protected function tearDown(): void
    {
        $this->removeTempDirectory();
        parent::tearDown();
    }

    /* TESTS */

    public function testForSimpleClass(): void
    {
        $classFile = __DIR__ . "/../../src/TestFileGenerator.php";
        $this->testFileGenerator->execute($classFile);

        $actual = "{$this->getTempDirectoryForTest()}/TestFileGeneratorTest.php";
        $expected = "{$this->getHelperDirectory()}/expected.txt";
        $this->assertFileEquals($expected, $actual);
    }

    /* HELPERS */

    private function getLocationProvider(): TestFileLocationProvider
    {
        return new class implements TestFileLocationProvider
        {
            public function getTestFileLocationForClass(ReflectionClass $class): string
            {
                return "/tmp/TestFileGeneratorIntegrationTest/{$class->getShortName()}Test.php";
            }
        };
    }

    private function getTempDirectoryForTest(): string
    {
        return '/tmp/TestFileGeneratorIntegrationTest';
    }

    private function getHelperDirectory(): string
    {
        return __DIR__ . '/__TestFileGeneratorIntegrationTest__';
    }

    private function createTempDirectory(): void
    {
        mkdir($this->getTempDirectoryForTest(), recursive: true);
    }

    private function removeTempDirectory(): void
    {
        (new Filesystem)->remove($this->getTempDirectoryForTest());
    }

    private function preparePHPProphet(): void
    {
        $php = $this->prophesizePHP('HJerichen\TestFileGenerator');
        $php->prepare('get_declared_classes');
    }
}
