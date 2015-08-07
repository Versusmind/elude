<?php namespace App\Libraries\Assets;

use League\Pipeline\PipelineBuilder;

/**
 * Class Orchestrator
 *
 *
 *
 * @package App\Libraries\Assets
 * @author  LAHAXE Arnaud
 */
class Orchestrator
{

    public function javascript($assets)
    {
        if (!is_array($assets)) {
            $assets = [$assets];
        }

        $pipelineBuilder = new PipelineBuilder;

        if (\App::environment('local')) {
            $pipelineBuilder->add(new Tasks\Copy());
        } else {
            $pipelineBuilder->add(new Tasks\Javascript\Min)
                ->pipe(new Tasks\Concat)
                ->add(new Tasks\Version);
        }

        return $pipelineBuilder->add(new Tasks\Javascript\Html)->build()->process($assets);
    }

    public function style($assets)
    {
        if (!is_array($assets)) {
            $assets = [$assets];
        }

        $pipelineBuilder = new PipelineBuilder;

        $pipelineBuilder->add(new Tasks\Sass\Compile)
            ->add(new Tasks\Less\Compile);

        if (\App::environment('local')) {
            $pipelineBuilder->add(new Tasks\Copy());
        } else {
            $pipelineBuilder->add(new Tasks\Css\Min)
                ->add(new Tasks\Concat)
                ->add(new Tasks\Version);
        }

        return $pipelineBuilder->add(new Tasks\Css\Html)->build()->process($assets);
    }
}