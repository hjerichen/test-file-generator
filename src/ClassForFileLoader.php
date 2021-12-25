<?php /** @noinspection PhpUnhandledExceptionInspection */
declare(strict_types=1);

namespace HJerichen\TestFileGenerator;

use ReflectionClass;
use RuntimeException;
use SplFileInfo;

class ClassForFileLoader
{
    private string $fileBasename;
    private string $classFile;

    public function loadClass(string $classFile): ReflectionClass
    {
        $this->classFile = $classFile;

        $this->includeClass();
        $this->extractFileBasename();
        return $this->getIncludedClassViaFileBasename();
    }

    private function includeClass(): void
    {
        /** @psalm-suppress UnresolvableInclude */
        include_once $this->classFile;
    }

    private function extractFileBasename(): void
    {
        $file = new SplFileInfo($this->classFile);
        $this->fileBasename = $file->getBasename('.php');
    }

    private function getIncludedClassViaFileBasename(): ReflectionClass
    {
        $classes = array_reverse(get_declared_classes());
        while ($class = array_shift($classes)) {
            $class = new ReflectionClass($class);
            $classShortName = $class->getShortName();
            if ($this->fileBasename === $classShortName) {
                return $class;
            }
        }
        throw new RuntimeException('Class not found.');
    }
}
