<?php namespace App\Libraries\Generator;

use Carbon\Carbon;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/******************************************************************************
 *
 * @package Myo 2
 * @copyright © 2015 by Versusmind.
 * All rights reserved. No part of this document may be
 * reproduced or transmitted in any form or by any means,
 * electronic, mechanical, photocopying, recording, or
 * otherwise, without prior written permission of Versusmind.
 * @link http://www.versusmind.eu/
 *
 * @file Generator.php
 * @author LAHAXE Arnaud
 * @last-edited 07/09/2015
 * @description Generator
 *
 ******************************************************************************/
class Generator
{
    private static $templateDataMapping = [
        'author' => 'AUTHOR_NAME',
        'modelName' => 'MODEL_NAME',
        'date' => 'DATE',
        'tableName' => 'MODEL_NAME_TABLE',
    ];

    protected $templatesDirectory;

    protected $userRestrictive;

    protected $modelName;

    protected $templateData = [];

    /**
     * @param $modelName
     * @param string $author
     */
    public function __construct($modelName, $userRestrictive = true, $author = '')
    {
        $this->modelName = $modelName;
        $this->userRestrictive = $userRestrictive;
        $this->templateData['author'] = $author;
        $this->templateData['modelName'] = ucfirst(camel_case($modelName));
        $this->templatesDirectory = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR;
        $this->templateData['date'] = Carbon::now()->toDateTimeString();
        $this->templateData['tableName'] = strtolower(str_plural($this->modelName));
    }

    /**
     *
     */
    public function generate()
    {
        $this->migration()
            ->model()
            ->repository()
            ->controller();
    }

    /**
     * @return $this
     */
    public function controller()
    {
        $this->template('Controller.php.txt', base_path('app' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'Api' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php'));
        $this->template('ControllerTest.php.txt', base_path('tests' . DIRECTORY_SEPARATOR . 'Api' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php'));

        return $this;
    }

    /**
     * @return $this
     */
    public function migration()
    {
        $template = 'Migration.php.txt';
        if($this->userRestrictive) {
            $template = 'MigrationUserRestrictive.php.txt';
        }

        $fileName = date('Y_m_d_His') . "_" . "create_" . $this->templateData['tableName'] . "_table.php";

        $this->template($template, base_path('database' . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . $fileName));

        return $this;
    }

    /**
     * @return $this
     */
    public function repository()
    {
        $this->template('Repository.php.txt', base_path('app' . DIRECTORY_SEPARATOR . 'Libraries' . DIRECTORY_SEPARATOR . 'Repositories' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php'));
        $this->template('RepositoryTest.php.txt', base_path('tests' . DIRECTORY_SEPARATOR . 'Unit' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php'));

        return $this;
    }

    /**
     * @return $this
     */
    public function model()
    {
        $template = 'Model.php.txt';
        if($this->userRestrictive) {
            $template = 'ModelUserRestrictive.php.txt';
        }

        $this->template($template, base_path('app' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php'));

        return $this;
    }

    protected function template($template, $destination)
    {
        $template = $this->templatesDirectory . $template;

        if (!is_file($template)) {
            throw new FileNotFoundException($template);
        }

        $code = file_get_contents($template);

        foreach (self::$templateDataMapping as $dataKey => $placeholder) {
            $code = str_replace('$$' . $placeholder . '$$', $this->templateData[$dataKey], $code);
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
}