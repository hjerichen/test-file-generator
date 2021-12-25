<?php declare(strict_types=1);

namespace HJerichen\TestFileGenerator\Test\Unit;

use HJerichen\TestFileGenerator\TestFileTemplateParser;
use PHPUnit\Framework\TestCase;

class TestFileTemplateParserTest extends TestCase
{
    private TestFileTemplateParser $templateParser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->templateParser = new TestFileTemplateParser();
    }

    /* TESTS */

    public function testExecute(): void
    {
        $data = ['testCaseClassNameShort' => 'TestCase'];

        $expected = $this->getExpected();
        $actual = $this->templateParser->execute($data);
        self::assertEquals($expected, $actual);
    }

    /* HELPERS */

    private function getExpected(): string
    {
        $expectedFile = __DIR__ . "/__TestFileTemplateParserTest__/expected.txt";
        return file_get_contents($expectedFile);
    }
}
