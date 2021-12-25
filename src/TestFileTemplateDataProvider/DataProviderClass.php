<?php declare(strict_types=1);

namespace HJerichen\TestFileGenerator\TestFileTemplateDataProvider;

use ReflectionClass;

class DataProviderClass implements DataProvider
{
    private ReflectionClass $class;

    public function setClass(ReflectionClass $class): void
    {
        $this->class = $class;
    }

    public function appendData(array &$data): void
    {
        $data['classNameShort'] = $this->class->getShortName();
        $data['className'] = $this->class->getName();
    }
}
