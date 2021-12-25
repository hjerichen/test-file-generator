<?php /** @noinspection PhpUnhandledExceptionInspection */
declare(strict_types=1);

namespace HJerichen\TestFileGenerator\Test\Unit;

use HJerichen\ProphecyPHP\NamespaceProphecy;
use HJerichen\ProphecyPHP\PHPProphetTrait;
use HJerichen\TestFileGenerator\ClassForFileLoader;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RuntimeException;

class ClassForFileLoaderTest extends TestCase
{
    use PHPProphetTrait;

    private ClassForFileLoader $classForFileLoader;
    private NamespaceProphecy $php;

    protected function setUp(): void
    {
        parent::setUp();
        $namespace = (new ReflectionClass(ClassForFileLoader::class))->getNamespaceName();
        $this->php = $this->prophesizePHP($namespace);

        $this->classForFileLoader = new ClassForFileLoader();
    }

    /* TESTS */

    public function testLoadClassForClassWasLastLoadedClass(): void
    {
        $this->setUpDeclaredClasses([
            ClassForFileLoader::class,
            __CLASS__
        ]);

        $expected = new ReflectionClass(self::class);
        $actual = $this->classForFileLoader->loadClass(__FILE__);
        self::assertEquals($expected, $actual);
    }

    public function testLoadClassForClassWasNotLastLoadedClass(): void
    {
        $this->setUpDeclaredClasses([
            __CLASS__,
            ClassForFileLoader::class,
        ]);

        $expected = new ReflectionClass(self::class);
        $actual = $this->classForFileLoader->loadClass(__FILE__);
        self::assertEquals($expected, $actual);
    }

    public function testLoadClassWithClassNotFound(): void
    {
        $this->setUpDeclaredClasses([
            ClassForFileLoader::class,
        ]);

        $exception = new RuntimeException('Class not found.');
        $this->expectExceptionObject($exception);

        $this->classForFileLoader->loadClass(__FILE__);
    }

    /* HELPERS */

    /** @param string[] $classes */
    private function setUpDeclaredClasses(array $classes): void
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->php->get_declared_classes()->willReturn($classes);
        $this->php->reveal();
    }
}
