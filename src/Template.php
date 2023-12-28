<?php

namespace Stagaires\PhpTemplate;

use Stagaires\PhpTemplate\Exception\TemplateNotFoundException;

class Template
{
    private string $path;
    private array $blocks = [];

    public function __construct($path = __DIR__ . '/views')
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
        $content = $this->extendPlaceholders($content);
        $content = $this->replaceForeachPlaceholders($content, $data);
        $content = $this->replaceIfElsePlaceholders($content, $data);
        $content = $this->captureBlockPlaceholders($content); // New method to capture blocks
        $content = $this->replaceBlockPlaceholders($content);
        $content = $this->replacePlaceholders($content, $data);
        return $this->removeBlockPlaceholders($content);
    }

    private function replacePlaceholders(string $content, array $data): string
    {
        foreach ($data as $key => $value) {
            $placeholder = '{{ $' . $key . ' }}';


            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    $subPlaceholder = '{{ $' . $key . '->' . $subKey . ' }}';

                    if (is_array($subValue)) {
                        $this->replacePlaceholders($content, $subValue);
                    }
                    else{
                        $content = str_replace($subPlaceholder, $subValue, $content);
                    }
                }
            }
            else{
                $content = str_replace($placeholder, $value, $content);
            }
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

    private function extendPlaceholders(string $content): string
    {
        return preg_replace_callback('~{{\s*extend\s+\'(\w+\/\w+)\'\s*}}~s', function ($matches) {
            $template = $matches[1];
            $path = $this->getTemplatePath($template);
            $this->validateTemplate($path);
            return $this->getTemplateContent($path);
        }, $content);
    }

    private function captureBlockPlaceholders(string $content): string
    {
        preg_replace_callback('/{{\s*block\s+"(\w+)"\s*}}(.*?){{\s*endblock\s*}}/s', function ($matches) {
            $blockName = $matches[1];
            $blockContent = $matches[2];
            $this->blocks[$blockName] = $blockContent;
        }, $content);
        return $content;
    }

    private function replaceBlockPlaceholders(string $content): string
    {
        foreach ($this->blocks as $blockName => $blockContent) {
            $blockPlaceholder = "{{ block \"$blockName\" }}{{ endblock }}";
            $content = str_replace($blockPlaceholder, $blockContent, $content);
        }

        return $content;
    }
    private function removeBlockPlaceholders(string $content): string
    {
        return preg_replace('/{{\s*block\s+".*?"\s*}}(.*?){{\s*endblock\s*}}/s', '', $content);
    }
    private function replaceIfElsePlaceholders(string $content, array $data): string
    {
        return preg_replace_callback(
            '/{{ *if +((\$\w+)) *== *"([^"]*)" *}}(.*?)(?:{{ *elseif +((\$\w+)) *== *"([^"]*)" *}}(.*?))?(?:{{ *else *}}(.*?))?{{ *endif *}}/s',
            function ($matches) use ($data) {
                $conditions = [];

                $conditions[] = [
                    'variable' => ltrim($matches[1], '$'),
                    'value' => $matches[3],
                    'content' => $matches[4],
                ];

                if (isset($matches[6])) {
                    $conditions[] = [
                        'variable' => ltrim($matches[6], '$'),
                        'value' => $matches[7],
                        'content' => $matches[8],
                    ];
                }

                if (isset($matches[9])) {
                    $conditions[] = [
                        'variable' => null,
                        'value' => null,
                        'content' => $matches[9],
                    ];
                }

                foreach ($conditions as $condition) {
                    $variable = $condition['variable'];
                    $conditionValue = $condition['value'];
                    $content = $condition['content'];
                    if ($variable === null) {
                        return $content;
                    }

                    $variableValue = $data[$variable] ?? null;
                    if ($variableValue === $conditionValue) {
                        return $content;
                    }
                }
                return '';
            },
            $content
        );
    }
}

