<?php declare(strict_types=1);

namespace HJerichen\TestFileGenerator;

use SebastianBergmann\Template\Template;

class TestFileTemplateParser
{
    private Template $template;

    public function __construct()
    {
        $this->template = new Template(__DIR__ . '/../templates/template.txt');
    }

    public function execute(array $data): string
    {
        $this->template->setVar($data);
        return $this->template->render();
    }
}
