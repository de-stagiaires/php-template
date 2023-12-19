<?php

namespace Stagaires\PhpTemplate\Exception;

use Exception;

class TemplateNotFoundException extends Exception
{
    public static function make(string $template): self
    {
        return new self("File '$template' not found.");
    }
}

