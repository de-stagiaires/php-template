<?php

namespace Stagaires\PhpTemplate;

use Stagaires\PhpTemplate\Exception\TemplateNotFoundException;

class Template
{
    private string $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @throws TemplateNotFoundException
     */
    public function render(string $template, array $data = []) : void
    {
        $path = $this->getTemplatePath($template);
        $this->validateTemplate($path);

        $content = $this->getTemplateContent($path);
        echo $this->renderTemplate($content, $data);
    }

    private function getTemplatePath(string $template): string
    {
        return $this->path . DIRECTORY_SEPARATOR . $template . '.php';
    }

    /**
     * @throws TemplateNotFoundException
     */
    private function validateTemplate(string $path): void
    {
        if (!file_exists($path)) {
            throw TemplateNotFoundException::make($path);
        }
    }

    private function getTemplateContent(string $path): string
    {
        return file_get_contents($path);
    }

    private function renderTemplate(string $content, array $data): string
    {
        $content = preg_replace_callback('/{{\s*foreach\s+(\$\w+)\s+as\s+(\$\w+)\s*}}(.*?){{\s*endforeach\s*}}/s', function ($matches) use ($data) {
            $output = '';
            $array = $data[ltrim($matches[1], '$')];

            foreach ($array as $item) {
                $data[$matches[2]] = $item; // Set the current item in the loop as a variable
                $output .= $this->renderTemplate($matches[3], $data); // Recursively render the loop content
            }

            return $output;
        }, $content);

        $placeholders = [];
        foreach ($data as $key => $value) {
            $placeholders[] = '{{ $' . $key . ' }}';
        }

        $content = str_replace($placeholders, array_values($data), $content);

        return $content;
    }

}