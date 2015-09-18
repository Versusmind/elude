<?php namespace App\Libraries\Generator;


use App\Libraries\Generator\Generators\Migration\Columns;
use App\Libraries\Generator\Generators\Migration\ExternalFields;
use App\Libraries\Generator\Generators\Model\BelongTo;
use App\Libraries\Generator\Generators\Model\Fillable;
use App\Libraries\Generator\Generators\Api\InputParameters;
use App\Libraries\Generator\Generators\Api\OutputParameters;
use App\Libraries\Generator\Generators\Model\Rules;
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
        'AUTHOR_NAME' => 'author',
        'MODEL_NAME' => 'modelName',
        'MODEL_NAME_LOWER_CASE' => 'modelNameLowerCase',
        'DATE' => 'date',
        'MODEL_NAME_TABLE' => 'tableName',
        'MODEL_NAME_PLURAL' => 'tableName',
        'MODEL_NAME_PLURAL_CAPITALIZED' => 'tableNameCapitalizes',
        'MIGRATION_FIELDS' => 'migrationFields',
        'FILLABLE_FIELDS' => 'fillableFields',
        'VALIDATORS' => 'validators',
        'INPUT_MODEL_PARAM_API' => 'inputModelParamApi',
        'OUTPUT_MODEL_ATTRIBUTE_API_CREATE' => 'outputModelAttributeApiCreate',
        'OUTPUT_MODEL_ATTRIBUTE_API_UPDATE' => 'outputModelAttributeApiUpdate',
        'OUTPUT_MODEL_ATTRIBUTE_API_SHOW' => 'outputModelAttributeApiShow',
        'BELONG_TO_FUNCTIONS' => 'belongToFunctions',
        'FOREIGN_KEY_FIELDS' => 'foreignKeyFields',
        'RELATIONS_API' => 'relationsApi',
        'RELATIONS_REPOSITORY' => 'relationsRepository'
    ];

    protected $templatesDirectory;

    protected $userRestrictive;

    protected $modelName;

    protected $templateData = [
        'migrationFields' => '',
        'fillableFields' => '',
        'validators' => '',
        'inputModelParamApi' => '',
        'outputModelAttributeApiCreate' => '',
        'outputModelAttributeApiUpdate' => '',
        'outputModelAttributeApiShow' => '',
        'belongToFunctions' => '',
        'foreignKeyFields' => '',
        'relationsApi' => '',
        'relationsRepository' => ''
    ];

    protected $files = [
        'migration' => '',
        'model' => '',
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
        $this->modelName = $modelName;
        $this->userRestrictive = $userRestrictive;
        $this->templateData['author'] = $author;
        $this->templateData['modelName'] = ucfirst(camel_case($modelName));
        $this->templatesDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR;
        $this->templateData['date'] = Carbon::now()->toDateTimeString();
        $this->templateData['tableName'] = strtolower(str_plural($this->modelName));
        $this->templateData['tableNameCapitalizes'] = ucfirst($this->templateData['tableName']);
        $this->templateData['modelNameLowerCase'] = strtolower($this->templateData['modelName']);

        $this->files = [
            'migration' => 'database' . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . date('Y_m_d_His') . "_" . "create_" . $this->templateData['tableName'] . "_table.php",
            'model' => 'app' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php',
            'repository' => 'app' . DIRECTORY_SEPARATOR . 'Libraries' . DIRECTORY_SEPARATOR . 'Repositories' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php',
            'controller' => 'app' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'Api' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php',
            'repositoryTest' => 'tests' . DIRECTORY_SEPARATOR . 'Unit' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php',
            'controllerTest' => 'tests' . DIRECTORY_SEPARATOR . 'Api' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php',
        ];

    }

    /**
     * @param array $fields
     * @param array $foreignKeys
     */
    public function generate(array $fields, array $foreignKeys)
    {
        $this->migration($fields, $foreignKeys)
            ->model($fields, $foreignKeys)
            ->repository($fields, $foreignKeys)
            ->controller($fields, $foreignKeys)
            ->route($foreignKeys);
    }

    /**
     * @param array $fields
     * @param array $foreignKeys
     * @return $this
     */
    public function controller(array $fields, array $foreignKeys)
    {

        $generator = new InputParameters([
            'fields' => $fields
        ]);

        $this->templateData['inputModelParamApi'] = $generator->generate();

        $generator = new OutputParameters([
            'fields' => $fields,
            'status' => 201
        ]);


        foreach($foreignKeys as $foreignKey) {
            $this->templateData['relationsApi'] .= $this->template('Relations' . DIRECTORY_SEPARATOR . 'Controller.php.txt', [
                'RELATION_CAPITALIZE' => 'relationCapitalize',
                'RELATION' => 'relation'
            ], [
                'relationCapitalize' => ucfirst($foreignKey),
                'relation' => strtolower($foreignKey)
            ]);
        }

        $this->templateData['outputModelAttributeApiCreate'] = $generator->generate();
        $this->templateData['outputModelAttributeApiUpdate'] = $generator->set('status', 202)->generate();
        $this->templateData['outputModelAttributeApiShow'] = $generator->set('status', 200)->generate();

        $this->writeTemplate('Controller.php.txt', base_path($this->files['controller']));
        $this->writeTemplate('ControllerTest.php.txt', base_path($this->files['controllerTest']));

        return $this;
    }

    /**
     * @param array $fields
     * @param array $foreignKeys
     * @return $this
     */
    public function migration(array $fields, array $foreignKeys)
    {
        $template = 'Migration.php.txt';

        $generator = new Columns([
            'fields' => $fields
        ]);

        $this->templateData['migrationFields'] = $generator->generate();

        $generator = new ExternalFields([
            'foreignKeys' => $foreignKeys
        ]);
        $this->templateData['foreignKeyFields'] = $generator->generate();


        $this->writeTemplate($template, base_path($this->files['migration']));

        return $this;
    }

    /**
     * @param array $fields
     * @param array $foreignKeys
     * @return $this
     */
    public function repository(array $fields, array $foreignKeys)
    {

        foreach($foreignKeys as $foreignKey) {
            $this->templateData['relationsRepository'] .= $this->template('Relations' . DIRECTORY_SEPARATOR . 'Repository.php.txt', [
                'RELATION_CAPITALIZE' => 'relationCapitalize',
                'RELATION' => 'relation'
            ], [
                'relationCapitalize' => ucfirst($foreignKey),
                'relation' => strtolower($foreignKey)
            ]);
        }

        $this->writeTemplate('Repository.php.txt', base_path($this->files['repository']));
        $this->writeTemplate('RepositoryTest.php.txt', base_path($this->files['repositoryTest']));

        return $this;
    }

    /**
     * @param array $foreignKeys
     * @return $this
     */
    public function route(array $foreignKeys)
    {
        $file = base_path('app' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'routes.php');
        $routeCode = file_get_contents($file);
        $pattern = '#\'api\/v1\'],\s+function\s+\(\)\s+use\s+\(\$app\)\s+\{#';


        $newRoutes = "\n        " . '$app->resource(\'' . $this->templateData['tableName'] . '\', \App\Http\Controllers\Api\\' . $this->templateData['modelName'] . '::class);';

        foreach($foreignKeys as $foreignKey) {
            $relationRouteCreate = "\n" . '        $app->post("%s/{id}/%s/{id%s}", ["as" => "%s.%s.store", "uses" => \App\Http\Controllers\Api\%s::class . "@%sStore"]);';
            $relationRouteDelete = "\n" . '        $app->delete("%s/{id}/%s/{id%s}", ["as" => "%s.%s.destroy", "uses" => \App\Http\Controllers\Api\%s::class . "@%sDestroy"]);' . "\n";

            $newRoutes .= sprintf($relationRouteCreate, $this->templateData['model'], str_plural($foreignKey), $this->templateData['model'], str_plural($foreignKey), ucfirst($this->templateData['model']), strtolower($foreignKey));
            $newRoutes .= sprintf($relationRouteDelete, $this->templateData['model'], str_plural($foreignKey), $this->templateData['model'], str_plural($foreignKey), ucfirst($this->templateData['model']), strtolower($foreignKey));
        }

        $routeCode = preg_replace($pattern, '$0' . $newRoutes, $routeCode);

        file_put_contents($file, $routeCode);

        return $this;
    }

    /**
     * @param array $fields
     * @param array $foreignKeys
     * @return $this
     */
    public function model(array $fields, array $foreignKeys)
    {
        $template = 'Model.php.txt';
        if ($this->userRestrictive) {
            $template = 'ModelUserRestrictive.php.txt';
        }

        $generator = new Fillable([
            'fields' => $fields
        ]);
        $this->templateData['fillableFields'] = $generator->generate();

        $generator = new Rules([
            'fields' => $fields
        ]);
        $this->templateData['validators'] = $generator->generate();

        $generator = new BelongTo([
            'foreignKeys' => $foreignKeys
        ]);
        $this->templateData['belongToFunctions'] = $generator->generate();

        $this->writeTemplate($template, base_path($this->files['model']));

        return $this;
    }

    /**
     * @param $template
     * @param $destination
     */
    protected function writeTemplate($template, $destination)
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
     * @param $template
     * @param array $placeholders
     * @param array $data
     * @return mixed|string
     */
    protected function template($template, array $placeholders = [], array $data = [])
    {
        $template = $this->templatesDirectory . $template;

        if (!is_file($template)) {
            throw new FileNotFoundException($template);
        }

        $code = file_get_contents($template);

        $data = array_merge($this->templateData, $data);
        $placeholders = array_merge(self::$templateDataMapping, $placeholders);

        foreach ($placeholders as $placeholder => $dataKey) {
            $code = str_replace(self::$varSeparator . $placeholder . self::$varSeparator, $data[$dataKey], $code);
        }

        return $code;
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