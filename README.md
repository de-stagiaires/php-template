# php-template
A basic template engine for PHP projects
## Installation
```bash
composer require stagaires/php-template
```
## Usage
To use the template engine, you need to create a new instance of the Template class. In the constructor, you can specify the path to the template files. If you do not specify a path, the default path will be used. The default path is the root path and then the folder views.
```php
$template = new Template("./path to template files");
```
To render a template, you can use the render method. The render method takes two parameters. The first parameter is the name of the template file. The second parameter is an array with the variables that you want to use in the template.
```php
$template->render("template file", ["variable" => "value"]);
```
## Template files
The template files are normal PHP files. You can use PHP code in the template files. To use a variable in the template file, you can use the following syntax:
```php
{{ $variable }}
```
To use a variable in a loop, you can use the following syntax:
```php
{{ foreach $variable as $item }}
    {{ $item }}
{{ endforeach }}
```
To use a variable in a conditional statement, you can use the following syntax:
```php
{{ if $variable == "value" }}
    {{ $variable }}
{{ endif }}
```
To use a variable in a conditional statement with an else statement, you can use the following syntax:
```php
{{ if $variable == "value" }}
    {{ $variable }}
{{ else }}
    {{ $variable }}
{{ endif }}
```
To use a variable in a conditional statement with an elseif statement, you can use the following syntax:
```php
{{ if $variable == "value" }}
    {{ $variable }}
{{ elseif $variable == "value" }}
    {{ $variable }}
{{ endif }}
```

To use a file as a template, you can use the following syntax:
```php
{{ extend "template file" }}
```

To use a block in a template file, you can use the following syntax:
```php
{{ block "block name" }}
    <p>Block content</p>
{{ endblock }}
```