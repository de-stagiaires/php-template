<?php
namespace Stagaires\PhpTemplate;

use Stagaires\PhpTemplate\Exception\TemplateNotFoundException;

class Template
{
    protected string $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @throws TemplateNotFoundException
     */
    public function render(string $template, array $data =[])
    {
        $path = $this->path . DIRECTORY_SEPARATOR . $template . '.php';

        if(! file_exists($path)) {
            throw TemplateNotFoundException::make($template);
        }
    }
}