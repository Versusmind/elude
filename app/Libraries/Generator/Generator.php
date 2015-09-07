<?php namespace App\Libraries\Generator;

use Carbon\Carbon;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/******************************************************************************
 *
 * @package     Myo 2
 * @copyright   © 2015 by Versusmind.
 * All rights reserved. No part of this document may be
 * reproduced or transmitted in any form or by any means,
 * electronic, mechanical, photocopying, recording, or
 * otherwise, without prior written permission of Versusmind.
 * @link        http://www.versusmind.eu/
 *
 * @file        Generator.php
 * @author      LAHAXE Arnaud
 * @last-edited 07/09/2015
 * @description Generator
 *
 ******************************************************************************/
class Generator
{

    public static $varSeparator = '$$';

    private static $templateDataMapping = [
        'AUTHOR_NAME'       => 'author',
        'MODEL_NAME'        => 'modelName',
        'DATE'              => 'date',
        'MODEL_NAME_TABLE'  => 'tableName',
        'MODEL_NAME_PLURAL' => 'tableName'
    ];

    protected $templatesDirectory;

    protected $userRestrictive;

    protected $modelName;

    protected $templateData = [];

    protected $files = [
        'migration' => '',
        'model'     => '',
        'repository' => '',
        'controller' => '',
        'repositoryTest' => '',
        'controllerTest' => '',

    ];

    /**
     * @param        $modelName
     * @param string $author
     */
    public function __construct($modelName, $userRestrictive = true, $author = '')
    {
        $this->modelName                 = $modelName;
        $this->userRestrictive           = $userRestrictive;
        $this->templateData['author']    = $author;
        $this->templateData['modelName'] = ucfirst(camel_case($modelName));
        $this->templatesDirectory        = __DIR__ . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR;
        $this->templateData['date']      = Carbon::now()->toDateTimeString();
        $this->templateData['tableName'] = strtolower(str_plural($this->modelName));

        $this->files = [
            'migration' => 'database' . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . date('Y_m_d_His') . "_" . "create_" . $this->templateData['tableName'] . "_table.php",
            'model'     => 'app' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php',
            'repository' => 'app' . DIRECTORY_SEPARATOR . 'Libraries' . DIRECTORY_SEPARATOR . 'Repositories' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php',
            'controller' => 'app' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'Api' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php',
            'repositoryTest' => 'tests' . DIRECTORY_SEPARATOR . 'Unit' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php',
            'controllerTest' => 'tests' . DIRECTORY_SEPARATOR . 'Api' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php',
        ];

    }

    /**
     *
     */
    public function generate()
    {
        $this->migration()
            ->model()
            ->repository()
            ->controller()
            ->route();
    }

    /**
     * @return $this
     */
    public function controller()
    {
        $this->template('Controller.php.txt', base_path($this->files['controller']));
        $this->template('ControllerTest.php.txt', base_path($this->files['controllerTest']));

        return $this;
    }

    /**
     * @return $this
     */
    public function migration()
    {
        $template = 'Migration.php.txt';
        if ($this->userRestrictive) {
            $template = 'MigrationUserRestrictive.php.txt';
        }

        $this->template($template, base_path($this->files['migration']));

        return $this;
    }

    /**
     * @return $this
     */
    public function repository()
    {
        $this->template('Repository.php.txt', base_path($this->files['repository']));
        $this->template('RepositoryTest.php.txt', base_path($this->files['repositoryTest']));

        return $this;
    }

    /**
     * @return $this
     */
    public function route()
    {
        $file = base_path('app' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'routes.php');
        $routeCode = file_get_contents($file);
        $pattern = '#\'api\/v1\'],\s+function\s+\(\)\s+use\s+\(\$app\)\s+\{#';
        $newRoute = "\n        " . '$app->resource(\'' . $this->templateData['tableName'] . '\', \App\Http\Controllers\Api\\' . $this->templateData['modelName'] . '::class);';
        $routeCode = preg_replace($pattern, '$0' . $newRoute, $routeCode);

        file_put_contents($file, $routeCode);

        return $this;
    }

    /**
     * @return $this
     */
    public function model()
    {
        $template = 'Model.php.txt';
        if ($this->userRestrictive) {
            $template = 'ModelUserRestrictive.php.txt';
        }

        $this->template($template, base_path($this->files['controllerTest']));

        return $this;
    }

    protected function template($template, $destination)
    {
        $template = $this->templatesDirectory . $template;

        if (!is_file($template)) {
            throw new FileNotFoundException($template);
        }

        $code = file_get_contents($template);

        foreach (self::$templateDataMapping as $placeholder => $dataKey) {
            $code = str_replace(self::$varSeparator . $placeholder . self::$varSeparator, $this->templateData[$dataKey], $code);
        }

        file_put_contents($destination, $code);
    }

    /**
     * @return string
     */
    public function getTemplatesDirectory()
    {
        return $this->templatesDirectory;
    }

    /**
     * @param string $templatesDirectory
     */
    public function setTemplatesDirectory($templatesDirectory)
    {
        $this->templatesDirectory = $templatesDirectory;
    }

    /**
     * @return boolean
     */
    public function isUserRestrictive()
    {
        return $this->userRestrictive;
    }

    /**
     * @param boolean $userRestrictive
     */
    public function setUserRestrictive($userRestrictive)
    {
        $this->userRestrictive = $userRestrictive;
    }

    /**
     * @return array
     */
    public function getTemplateData()
    {
        return $this->templateData;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }
}