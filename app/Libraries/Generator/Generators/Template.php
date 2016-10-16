<?php
/**
 * User: LAHAXE Arnaud
 * Date: 01/10/2015
 * Time: 14:15
 * FileName : Template.php
 * Project : myo2
 */

namespace App\Libraries\Generator\Generators;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use TwigBridge\Facade\Twig;

class Template
{

    protected $templateDirectory;

    /**
     * Template constructor.
     */
    public function __construct()
    {
        $this->templatesDirectory = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR;
    }

    public function compileFile($path, $data = [])
    {
        $path = $this->templatesDirectory . $path;

        if (!is_file($path)) {
            throw new FileNotFoundException($path);
        }

        $code = file_get_contents($path);

        return $this->compile($code, $data);
    }

    public function compile($template, $data = [])
    {
        $template = $this->templatesDirectory . $template;

        if (!is_file($template)) {
            throw new FileNotFoundException($template);
        }

        return Twig::render($template, $data);
    }
}