<?php declare(strict_types=1);

namespace HJerichen\TestFileGenerator\Test\Unit\TestFileTemplateDataProvider;

use HJerichen\TestFileGenerator\TestFileTemplateDataProvider\DataProviderNamespace;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use ReflectionClass;

class DataProviderNamespaceTest extends TestCase
{
    use ProphecyTrait;

    private DataProviderNamespace $dataProvider;
    private ObjectProphecy $class;
    private array $data;

    protected function setUp(): void
    {
        parent::setUp();
        $this->class = $this->prophesize(ReflectionClass::class);
        $this->data = [];

        $this->dataProvider = new DataProviderNamespace();
        $this->dataProvider->setClass($this->class->reveal());
    }

    /* TESTS */

    public function testAppendDataWithFourParts(): void
    {
        $this->class->getNamespaceName()->willReturn('One\Two\Three\Four');

        $this->dataProvider->appendData($this->data);
        $this->assertDatIsEqualTo([
            'namespace' => 'One\Two\Test\Unit\Three\Four'
        ]);

    }

    public function testAppendDataWithThreeParts(): void
    {
        $this->class->getNamespaceName()->willReturn('One\Two\Three');

        $this->dataProvider->appendData($this->data);
        $this->assertDatIsEqualTo([
            'namespace' => 'One\Two\Test\Unit\Three'
        ]);

    }

    /* HELPERS */

    private function assertDatIsEqualTo(array $expected): void
    {
        $actual = $this->data;
        self::assertEquals($expected, $actual);
    }
}
