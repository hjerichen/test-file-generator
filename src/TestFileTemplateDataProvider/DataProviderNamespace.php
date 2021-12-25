<?php declare(strict_types=1);

namespace HJerichen\TestFileGenerator\TestFileTemplateDataProvider;

use ReflectionClass;

class DataProviderNamespace implements DataProvider
{
    private ReflectionClass $class;

    public function setClass(ReflectionClass $class): void
    {
        $this->class = $class;
    }

    public function appendData(array &$data): void
    {
        $data['namespace'] = $this->buildNamespace();
    }

    public function buildNamespace(): string
    {
        $namespaceParts = explode('\\', $this->class->getNamespaceName());
        array_splice($namespaceParts, 2, 0, ['Test', 'Unit']);
        return implode('\\', $namespaceParts);
    }
}
