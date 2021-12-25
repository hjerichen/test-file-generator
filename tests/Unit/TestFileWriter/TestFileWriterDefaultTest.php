<?php declare(strict_types=1);

namespace HJerichen\TestFileGenerator\Test\Unit\TestFileWriter;

use HJerichen\TestFileGenerator\TestFileWriter\TestFileWriterDefault;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class TestFileWriterDefaultTest extends TestCase
{
    private TestFileWriterDefault $writer;
    private string $targetBaseDirectory;
    private string $content;

    protected function setUp(): void
    {
        parent::setUp();
        $this->targetBaseDirectory = '/tmp/TestFileWriterDefaultTest';
        $this->content = 'TestFile';

        $this->writer = new TestFileWriterDefault();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        (new Filesystem())->remove($this->targetBaseDirectory);
    }


    /* TESTS */

    public function testWriteTestFileWithFolderDoesNotExist(): void
    {
        $target = "$this->targetBaseDirectory/Test.php";
        $this->writer->writeTestFile($target, $this->content);

        $expected = $this->content;
        $actual = file_get_contents($target);
        self::assertEquals($expected, $actual);
    }

    public function testWriteTestFileWithFolderDoesExist(): void
    {
        mkdir($this->targetBaseDirectory);

        $target = "$this->targetBaseDirectory/Test.php";
        $this->writer->writeTestFile($target, $this->content);

        $expected = $this->content;
        $actual = file_get_contents($target);
        self::assertEquals($expected, $actual);
    }
}
