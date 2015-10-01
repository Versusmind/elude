<?php namespace App\Libraries\Generator;

use App\Libraries\Generator\Generators\Api\InputParameters;
use App\Libraries\Generator\Generators\Api\OutputParameters;
use App\Libraries\Generator\Generators\Migration\BelongTo as BelongToMigration;
use App\Libraries\Generator\Generators\Migration\Columns;
use App\Libraries\Generator\Generators\Model\BelongTo as BelongToModel;
use App\Libraries\Generator\Generators\Model\Fillable;
use App\Libraries\Generator\Generators\Model\Rules;
use App\Libraries\Generator\Generators\Route\BelongTo as BelongToRoute;;
use App\Libraries\Generator\Generators\Route\Resource;
use App\Libraries\Generator\Generators\Template;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

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

    protected $userRestrictive;

    protected $templateData = [
        'author'                        => '',
        'modelName'                     => '',
        'modelNameLowerCase'            => '',
        'date'                          => '',
        'tableName'                     => '',
        'tableNameCapitalizes'          => '',
        'migrationFields'               => '',
        'fillableFields'                => '',
        'validators'                    => '',
        'inputModelParamApi'            => '',
        'outputModelAttributeApiCreate' => '',
        'outputModelAttributeApiUpdate' => '',
        'outputModelAttributeApiShow'   => '',
        'belongToFunctions'             => '',
        'foreignKeyFields'              => '',
        'relationsApi'                  => '',
        'relationsRepository'           => ''
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
     * @var Template
     */
    protected $templateCompiler;

    /**
     * @param        $modelName
     * @param string $author
     */
    public function __construct($modelName, $userRestrictive = true, $author = '')
    {
        $this->userRestrictive                      = $userRestrictive;
        $this->templateData['author']               = $author;
        $this->templateData['modelName']            = ucfirst(camel_case($modelName));
        $this->templateData['date']                 = Carbon::now()->toDateTimeString();
        $this->templateData['tableName']            = strtolower(str_plural($modelName));
        $this->templateData['tableNameCapitalizes'] = ucfirst($this->templateData['tableName']);
        $this->templateData['modelNameLowerCase']   = strtolower($this->templateData['modelName']);

        $this->files = [
            'migration'      => 'database' . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . date('Y_m_d_His') . "_" . "create_" . $this->templateData['tableName'] . "_table.php",
            'model'          => 'app' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php',
            'repository'     => 'app' . DIRECTORY_SEPARATOR . 'Libraries' . DIRECTORY_SEPARATOR . 'Repositories' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php',
            'controller'     => 'app' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'Api' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php',
            'repositoryTest' => 'tests' . DIRECTORY_SEPARATOR . 'Unit' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php',
            'controllerTest' => 'tests' . DIRECTORY_SEPARATOR . 'Api' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . $this->templateData['modelName'] . '.php',
        ];

        $this->templateCompiler = App::make(Template::class);
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
     *
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

        foreach ($foreignKeys as $foreignKey) {
            $this->templateData['relationsApi'] .= $this->template('Relations' . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . 'BelongTo.php.twig', [
                'relationCapitalize' => ucfirst($foreignKey),
                'relation'           => strtolower($foreignKey)
            ]);
        }

        $this->templateData['outputModelAttributeApiCreate'] = $generator->generate();
        $this->templateData['outputModelAttributeApiUpdate'] = $generator->set('status', 202)->generate();
        $this->templateData['outputModelAttributeApiShow']   = $generator->set('status', 200)->generate();

        $this->writeTemplate('Controller.php.twig', base_path($this->files['controller']));
        $this->writeTemplate('ControllerTest.php.twig', base_path($this->files['controllerTest']));

        return $this;
    }

    /**
     * @param array $fields
     * @param array $foreignKeys
     *
     * @return $this
     */
    public function migration(array $fields, array $foreignKeys)
    {
        $template = 'Migration.php.twig';

        $generator = new Columns([
            'fields' => $fields
        ]);

        $this->templateData['migrationFields'] = $generator->generate();

        $generator                              = new BelongToMigration ([
            'foreignKeys' => $foreignKeys
        ]);
        $this->templateData['foreignKeyFields'] = $generator->generate();

        $this->writeTemplate($template, base_path($this->files['migration']));

        return $this;
    }

    /**
     * @param array $fields
     * @param array $foreignKeys
     *
     * @return $this
     */
    public function repository(array $fields, array $foreignKeys)
    {

        foreach ($foreignKeys as $foreignKey) {
            $this->templateData['relationsRepository'] .= $this->template('Relations' . DIRECTORY_SEPARATOR . 'Repository' . DIRECTORY_SEPARATOR . 'BelongTo.php.twig', [
                'relationCapitalize' => ucfirst($foreignKey),
                'relation'           => strtolower($foreignKey)
            ]);
        }

        $this->writeTemplate('Repository.php.twig', base_path($this->files['repository']));
        $this->writeTemplate('RepositoryTest.php.twig', base_path($this->files['repositoryTest']));

        return $this;
    }

    /**
     * @param array $foreignKeys
     *
     * @return $this
     */
    public function route(array $foreignKeys)
    {
        $file      = base_path('app' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'routes.php');
        $routeCode = file_get_contents($file);
        $pattern   = '#\'api\/v1\'],\s+function\s+\(\)\s+use\s+\(\$app\)\s+\{#';

        $generator = new Resource([
            'model' => $this->templateData['modelName'],
        ]);

        $newRoutes = $generator->generate();

        $generator = new BelongToRoute([
            'model'       => $this->templateData['modelName'],
            'foreignKeys' => $foreignKeys
        ]);

        $newRoutes .= $generator->generate();

        $routeCode = preg_replace($pattern, '$0' . $newRoutes, $routeCode);

        file_put_contents($file, $routeCode);

        return $this;
    }

    /**
     * @param array $fields
     * @param array $foreignKeys
     *
     * @return $this
     */
    public function model(array $fields, array $foreignKeys)
    {
        $template = 'Model.php.twig';
        if ($this->userRestrictive) {
            $template = 'ModelUserRestrictive.php.twig';
        }

        $generator                            = new Fillable([
            'fields' => $fields
        ]);
        $this->templateData['fillableFields'] = $generator->generate();

        $generator                        = new Rules([
            'fields' => $fields
        ]);
        $this->templateData['validators'] = $generator->generate();

        $generator                               = new BelongToModel([
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

        file_put_contents($destination, $this->templateCompiler->compile($template, $this->templateData));
    }

    /**
     *
     * @param       $template
     * @param array $data
     *
     * @return mixed
     */
    protected function template($template, array $data = [])
    {
        $data = array_merge($this->templateData, $data);

        return $this->templateCompiler->compile($template, $data);
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