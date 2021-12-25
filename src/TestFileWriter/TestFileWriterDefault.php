<?php declare(strict_types=1);

namespace HJerichen\TestFileGenerator\TestFileWriter;

use SplFileInfo;

class TestFileWriterDefault implements TestFileWriter
{
    public function writeTestFile(string $target, string $content): void
    {
        $this->createDirectory($target);
        file_put_contents($target, $content);
    }

    private function createDirectory(string $target): void
    {
        $directory = (new SplFileInfo($target))->getPath();
        if (is_dir($directory)) return;

        mkdir($directory, recursive: true);
    }
}
