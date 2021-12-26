<?php declare(strict_types=1);

namespace HJerichen\TestFileGenerator\TestFileTemplateDataProvider;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;

class DataProviderProphecies implements DataProvider
{
    /** @var ReflectionParameter[] */
    private array $constructorParameters;

    public function setClass(ReflectionClass $class): void
    {
        $constructor = $class->getConstructor();
        $this->constructorParameters = $constructor?->getParameters() ?? [];
    }

    public function appendData(array &$data): void
    {
        $data['prophecyUses'] = $this->buildUses();
        $data['prophecyProperties'] = $this->buildProperties();
        $data['prophecyInitialize'] = $this->buildInitializes();
    }

    private function buildUses(): string
    {
        $uses = array_map(callback: [$this, 'buildUse'], array: $this->constructorParameters);
        if (count($uses) > 0) {
            $uses[] = 'use Prophecy\Prophecy\ObjectProphecy;';
        }
        return implode(separator: "\n", array: $uses);
    }

    private function buildUse(ReflectionParameter $parameter): string
    {
        $typeName = $this->getTypeFullName($parameter);
        return "use $typeName;";
    }

    private function buildProperties(): string
    {
        $properties = array_map(callback: [$this, 'buildProperty'], array: $this->constructorParameters);
        return implode(separator: "\n    ", array: $properties);
    }

    private function buildProperty(ReflectionParameter $parameter): string
    {
        $name = $parameter->getName();
        return "private ObjectProphecy $$name;";
    }

    private function buildInitializes(): string
    {
        $initializes = array_map(callback: [$this, 'buildInitialize'], array: $this->constructorParameters);
        return implode(separator: "\n        ", array: $initializes);
    }

    private function buildInitialize(ReflectionParameter $parameter): string
    {
        $name = $parameter->getName();
        $class = $this->getTypeShortName($parameter);
        return "\$this->$name = \$this->prophesize($class::class);";
    }

    private function getTypeFullName(ReflectionParameter $parameter): string
    {
        if (!($parameter->getType() instanceof ReflectionNamedType)) return '';

        return $parameter->getType()->getName();
    }

    private function getTypeShortName(ReflectionParameter $parameter): string
    {
        if (!($parameter->getType() instanceof ReflectionNamedType)) return '';

        $explodedType = explode(separator: '\\', string: $parameter->getType()->getName());
        return array_reverse($explodedType)[0];
    }
}
