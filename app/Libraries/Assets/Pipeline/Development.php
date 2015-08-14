<?php namespace App\Libraries\Assets\Pipeline;


use App\Libraries\Assets\Asset;
use App\Libraries\Assets\Tasks\Cleaner;
use App\Libraries\Assets\Tasks\Copy;
use App\Libraries\Assets\Tasks\Css\Rewrite;
use App\Libraries\Assets\Tasks\Javascript\Html as JsRenderer;
use League\Pipeline\PipelineBuilder;
use App\Libraries\Assets\Tasks\Css\Html as CssRenderer;
use App\Libraries\Assets\Tasks\Less\Compile as LessCompiler;
use App\Libraries\Assets\Tasks\Sass\Compile as SassCompiler;

/**
 * Class Development
 * @package App\Libraries\Assets\Pipeline
 */
class Development extends Pipeline
{

    /**
     * @param bool|true $renderer
     * @param bool|true $build
     * @return \League\Pipeline\Pipeline
     */
    public function javascript($renderer = true, $build = true)
    {
        $pipelineBuilder = new PipelineBuilder;

        if ($build) {
            $pipelineBuilder->add(new Copy(Asset::JS))
                ->add(new Cleaner);
        }

        if ($renderer) {
            $pipelineBuilder->add(new JsRenderer);
        }

        return $pipelineBuilder->build();
    }

    /**
     * @param bool|true $renderer
     * @param bool|true $build
     * @return \League\Pipeline\Pipeline
     */
    public function style($renderer = true, $build = true)
    {
        $pipelineBuilder = new PipelineBuilder;

        if ($build) {
            $pipelineBuilder->add(new SassCompiler)
                ->add(new LessCompiler())
                ->add(new Copy(Asset::CSS))
                ->add(new Rewrite())
                ->add(new Cleaner());
        }

        if ($renderer) {
            $pipelineBuilder->add(new CssRenderer());
        }

        return $pipelineBuilder->build();
    }
}