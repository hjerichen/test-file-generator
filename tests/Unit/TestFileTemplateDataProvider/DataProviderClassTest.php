<?php declare(strict_types=1);

namespace HJerichen\TestFileGenerator\Test\Unit\TestFileTemplateDataProvider;

use HJerichen\TestFileGenerator\TestFileTemplateDataProvider\DataProviderClass;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use ReflectionClass;

class DataProviderClassTest extends TestCase
{
    use ProphecyTrait;

    private DataProviderClass $dataProvider;
    private ObjectProphecy $class;
    private array $data;

    protected function setUp(): void
    {
        parent::setUp();
        $this->class = $this->prophesize(ReflectionClass::class);
        $this->data = [];

        $this->dataProvider = new DataProviderClass();
        $this->dataProvider->setClass($this->class->reveal());
    }

    /* TESTS */

    public function testAppendData(): void
    {
        $this->class->getName()->willReturn('FullName');
        $this->class->getShortName()->willReturn('ShortName');

        $this->dataProvider->appendData($this->data);
        $this->assertDataIsEqualTo([
            'classNameShort' => 'ShortName',
            'className' => 'FullName',
        ]);
    }

    /* HELPERS */

    private function assertDataIsEqualTo(array $expected): void
    {
        $actual = $this->data;
        self::assertEquals($expected, $actual);
    }
}
