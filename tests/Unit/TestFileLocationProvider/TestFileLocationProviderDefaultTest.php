<?php declare(strict_types=1);

namespace HJerichen\TestFileGenerator\Test\Unit\TestFileLocationProvider;

use HJerichen\TestFileGenerator\TestFileLocationProvider\TestFileLocationProviderDefault;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use ReflectionClass;

class TestFileLocationProviderDefaultTest extends TestCase
{
    use ProphecyTrait;

    private TestFileLocationProviderDefault $locationProvider;
    private ObjectProphecy $class;

    protected function setUp(): void
    {
        parent::setUp();
        $this->class = $this->prophesize(ReflectionClass::class);

        $this->locationProvider = new TestFileLocationProviderDefault();
    }

    /* TESTS */

    public function testResult(): void
    {
        $this->class->getFileName()->willReturn('/to/project/src/Sub/Class.php');

        $this->assertLocationIsEqualTo('/to/project/tests/Unit/Sub/ClassTest.php');
    }

    /* HELPERS */

    private function assertLocationIsEqualTo(string $expected): void
    {
        /** @psalm-suppress MixedArgument */
        $actual = $this->locationProvider->getTestFileLocationForClass($this->class->reveal());
        self::assertEquals($expected, $actual);
    }
}
