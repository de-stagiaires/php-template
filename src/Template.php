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
    public function render(string $template, array $data = []): void
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

        $content = $this->replaceForeachPlaceholders($content, $data);
        $content = $this->replacePlaceholders($content, $data);

        return $content;
    }

    private function replacePlaceholders(string $content, array $data): string
    {
        foreach ($data as $key => $value) {
            $placeholder = '{{ $' . $key . ' }}';

            if (is_array($value)) {
                $value = implode('', $value);
            }

            $content = str_replace($placeholder, $value, $content);
        }

        return $content;
    }

    private function replaceForeachPlaceholders(string $content, array $data): string
    {
       return preg_replace_callback('/{{\s*foreach\s+(\$\w+)\s+as\s+(\$\w+)\s*}}(.*?){{\s*endforeach\s*}}/s', function ($matches) use ($data) {
            $output = '';
            $array = $data[ltrim($matches[1], '$')];
            $loopVar = ltrim($matches[2], '$');

            foreach ($array as $item) {
                $loopData = $data;
                $loopData[$loopVar] = $item;
                $output .= $this->replacePlaceholders($matches[3], $loopData);
            }
            return $output;
        }, $content);
    }


}

