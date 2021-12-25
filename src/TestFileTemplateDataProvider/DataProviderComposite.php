<?php declare(strict_types=1);

namespace HJerichen\TestFileGenerator\TestFileTemplateDataProvider;

use HJerichen\Collections\Collection;
use HJerichen\Collections\ObjectCollection;
use ReflectionClass;

/**
 * @extends Collection<DataProvider>
 * @extends ObjectCollection<DataProvider>
 */
class DataProviderComposite extends ObjectCollection implements DataProvider
{
    /** @param DataProvider[] $items */
    public function __construct(array $items = [])
    {
        parent::__construct(DataProvider::class, $items);
    }

    public function setClass(ReflectionClass $class): void
    {
        foreach ($this->items as $item) {
            $item->setClass($class);
        }
    }

    public function appendData(array &$data): void
    {
        foreach ($this->items as $item) {
            $item->appendData($data);
        }
    }
}
