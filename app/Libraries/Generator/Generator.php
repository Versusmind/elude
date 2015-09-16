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
        'AUTHOR_NAME'                       => 'author',
        'MODEL_NAME'                        => 'modelName',
        'MODEL_NAME_LOWER_CASE'             => 'modelNameLowerCase',
        'DATE'                              => 'date',
        'MODEL_NAME_TABLE'                  => 'tableName',
        'MODEL_NAME_PLURAL'                 => 'tableName',
        'MODEL_NAME_PLURAL_CAPITALIZED'     => 'tableNameCapitalizes',
        'MIGRATION_FIELDS'                  => 'migrationFields',
        'FILLABLE_FIELDS'                   => 'fillableFields',
        'VALIDATORS'                        => 'validators',
        'INPUT_MODEL_PARAM_API'             => 'inputModelParamApi',
        'OUTPUT_MODEL_ATTRIBUTE_API_CREATE' => 'outputModelAttributeApiCreate',
        'OUTPUT_MODEL_ATTRIBUTE_API_UPDATE' => 'outputModelAttributeApiUpdate',
        'OUTPUT_MODEL_ATTRIBUTE_API_SHOW'   => 'outputModelAttributeApiShow',
    ];

    protected $templatesDirectory;

    protected $userRestrictive;

    protected $modelName;

    protected $templateData = [
        'migrationFields'               => '',
        'fillableFields'                => '',
        'validators'                    => '',
        'inputModelParamApi'            => '',
        'outputModelAttributeApiCreate' => '',
        'outputModelAttributeApiUpdate' => '',
        'outputModelAttributeApiShow'   => '',
    ];

    protected $files = [
        'migration'      => '',
        'model'          => '',
        'repository'     => '',
        'controller'     => '',
        'repositoryTest' => '',
        'controllerTest' => '',
    ];

    /**
     * @param        $modelName
     * @param string $author
     */
    public function __construct($modelName, $userRestrictive = true, $author = '')
    {
        $this->modelName                            = $modelName;
        $this->userRestrictive                      = $userRestrictive;
        $this->templateData['author']               = $author;
        $this->templateData['modelName']            = ucfirst(camel_case($modelName));
        $this->templatesDirectory                   = __DIR__ . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR;
        $this->templateData['date']                 = Carbon::now()->toDateTimeString();
        $this->templateData['tableName']            = strtolower(str_plural($this->modelName));
        $this->templateData['tableNameCapitalizes'] = ucfirst($this->templateData['tableName']);
        $this->templateData['modelNameLowerCase']   = strtolower($this->templateData['tableName']);

        $this->files = [
            'migration'      => 'database' . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . date('Y_m_d_His') . "_" . "create_" . $this->templateData['tableName'] . "_table.php",
            'model'          => 'app' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php',
            'repository'     => 'app' . DIRECTORY_SEPARATOR . 'Libraries' . DIRECTORY_SEPARATOR . 'Repositories' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php',
            'controller'     => 'app' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'Api' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php',
            'repositoryTest' => 'tests' . DIRECTORY_SEPARATOR . 'Unit' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php',
            'controllerTest' => 'tests' . DIRECTORY_SEPARATOR . 'Api' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php',
        ];

    }

    /**
     * @author LAHAXE Arnaud
     *
     * @param array $fields
     *
     */
    public function generate(array $fields)
    {
        $this->migration($fields)
            ->model($fields)
            ->repository($fields)
            ->controller($fields)
            ->route();
    }

    /**
     * @author LAHAXE Arnaud
     *
     * @param array $fields
     *
     * @return $this
     */
    public function controller(array $fields)
    {

        $inputModelParamApi            = [];
        $outputModelAttributeApiCreate = [];
        $outputModelAttributeApiUpdate = [];
        $outputModelAttributeApiShow   = [];

        foreach ($fields as $field) {
            $inputModelParamApi[] = sprintf('* @apiParam {%s} %s %s.', $field['apiType'], $field['name'], ucfirst($field['name']));

            $outputModelAttributeApiCreate[] = sprintf('* @apiSuccess (%d) {%s} %s %s.', 201, $field['apiType'], $field['name'], ucfirst($field['name']));
            $outputModelAttributeApiUpdate[] = sprintf('* @apiSuccess (%d) {%s} %s %s.', 202, $field['apiType'], $field['name'], ucfirst($field['name']));
            $outputModelAttributeApiShow[]   = sprintf('* @apiSuccess (%d) {%s} %s %s.', 200, $field['apiType'], $field['name'], ucfirst($field['name']));
        }

        $this->templateData['inputModelParamApi']            = join("\n         ", $inputModelParamApi);
        $this->templateData['outputModelAttributeApiCreate'] = join("\n         ", $outputModelAttributeApiCreate);
        $this->templateData['outputModelAttributeApiUpdate'] = join("\n         ", $outputModelAttributeApiUpdate);
        $this->templateData['outputModelAttributeApiShow']   = join("\n         ", $outputModelAttributeApiShow);

        $this->template('Controller.php.txt', base_path($this->files['controller']));
        $this->template('ControllerTest.php.txt', base_path($this->files['controllerTest']));

        return $this;
    }

    /**
     * @author LAHAXE Arnaud
     *
     * @param array $fields
     *
     * @return $this
     */
    public function migration(array $fields)
    {
        $template = 'Migration.php.txt';
        if ($this->userRestrictive) {
            $template = 'MigrationUserRestrictive.php.txt';
        }

        $migrationFields = [];
        foreach ($fields as $field) {
            $migration = '$table->' . $field['type'] . '("' . $field['name'] . '")';
            if ($field['nullable']) {
                $migration .= '->nullable()';
            }
            $migration .= ';';

            $migrationFields[] = $migration;
        }

        $this->templateData['migrationFields'] = join("\n            ", $migrationFields);

        $this->template($template, base_path($this->files['migration']));

        return $this;
    }

    /**
     * @author LAHAXE Arnaud
     *
     * @param array $fields
     *
     * @return $this
     */
    public function repository(array $fields)
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
        $file      = base_path('app' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'routes.php');
        $routeCode = file_get_contents($file);
        $pattern   = '#\'api\/v1\'],\s+function\s+\(\)\s+use\s+\(\$app\)\s+\{#';
        $newRoute  = "\n        " . '$app->resource(\'' . $this->templateData['tableName'] . '\', \App\Http\Controllers\Api\\' . $this->templateData['modelName'] . '::class);';
        $routeCode = preg_replace($pattern, '$0' . $newRoute, $routeCode);

        file_put_contents($file, $routeCode);

        return $this;
    }

    /**
     * @return $this
     */
    public function model(array $fields)
    {
        $template = 'Model.php.txt';
        if ($this->userRestrictive) {
            $template = 'ModelUserRestrictive.php.txt';
        }

        $fillable   = [];
        $validators = [];
        foreach ($fields as $field) {

            $fillable[] = "'" . $field['name'] . "'";

            $rules = '';
            if ($field['required']) {
                $rules = 'required|';
            }

            $rules .= $field['rules'];

            $validators[] = "'" . $field['name'] . "' => '" . $rules . "'";
        }

        $this->templateData['fillableFields'] = join(",\n", $fillable);
        $this->templateData['validators']     = join(",\n", $validators);

        $this->template($template, base_path($this->files['model']));

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