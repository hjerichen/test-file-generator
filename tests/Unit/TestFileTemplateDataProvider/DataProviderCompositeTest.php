<?php declare(strict_types=1);

namespace HJerichen\TestFileGenerator\Test\Unit\TestFileTemplateDataProvider;

use HJerichen\TestFileGenerator\TestFileTemplateDataProvider\DataProvider;
use HJerichen\TestFileGenerator\TestFileTemplateDataProvider\DataProviderComposite;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use ReflectionClass;

class DataProviderCompositeTest extends TestCase
{
    use ProphecyTrait;

    private DataProviderComposite $dataProviderComposite;
    /** @var ReflectionClass<self> */
    private ReflectionClass $class;

    private ObjectProphecy $dataProvider1;
    private ObjectProphecy $dataProvider2;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dataProvider1 = $this->prophesize(DataProvider::class);
        $this->dataProvider2 = $this->prophesize(DataProvider::class);
        $this->class = new ReflectionClass($this);

        $this->dataProviderComposite = new DataProviderComposite();
        $this->dataProviderComposite[] = $this->dataProvider1->reveal();
        $this->dataProviderComposite[] = $this->dataProvider2->reveal();
    }

    /* TESTS */

    public function testSetClass(): void
    {
        $this->dataProvider1->setClass($this->class)->shouldBeCalledOnce();
        $this->dataProvider2->setClass($this->class)->shouldBeCalledOnce();

        $this->dataProviderComposite->setClass($this->class);
    }

    public function testAppendDate(): void
    {
        $data = [];
        $this->dataProvider1->appendData($data)->shouldBeCalledOnce();
        $this->dataProvider2->appendData($data)->shouldBeCalledOnce();

        $this->dataProviderComposite->appendData($data);
    }
}
