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
    public function render(string $template, array $data = []): string
    {
        $path = $this->getTemplatePath($template);
        $this->validateTemplate($path);

        $content = $this->getTemplateContent($path);
        $content = $this->modifyTemplateContent($content);

        return $this->renderTemplate($content, $data);
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

    private function modifyTemplateContent(string $content): string
    {
        return str_replace('{{', '<?= ', str_replace('}}', ' ?>', $content));
    }

    private function renderTemplate(string $content, array $data): string
    {
        extract($data);
        ob_start();
        require $content;
        return ob_get_clean();
    }
}