<?php declare(strict_types=1);

namespace HJerichen\TestFileGenerator\TestFileWriter;

interface TestFileWriter
{
    public function writeTestFile(string $target, string $content): void;
}
