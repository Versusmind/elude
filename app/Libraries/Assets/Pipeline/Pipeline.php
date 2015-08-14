<?php namespace App\Libraries\Assets\Pipeline;

use App\Libraries\Assets\Asset;
use App\Libraries\Assets\Tasks\Copy;
use League\Pipeline\PipelineBuilder;

/**
 * Class Pipeline
 * @package App\Libraries\Assets\Pipeline
 */
abstract class Pipeline
{
    /**
     * @return \League\Pipeline\Pipeline
     */
    public function font ()
    {
        return (new PipelineBuilder())->add(new Copy(Asset::FONT))
            ->build();
    }

    /**
     * @return \League\Pipeline\Pipeline
     */
    public function image ()
    {
        return (new PipelineBuilder)->add(new Copy(Asset::IMG))
            ->build();
    }

    /**
     * @param bool|true $renderer
     * @param bool|true $build
     * @return \League\Pipeline\Pipeline
     */
    public abstract function javascript ($renderer = true, $build = true);


    /**
     * @param bool|true $renderer
     * @param bool|true $build
     * @return \League\Pipeline\Pipeline
     */
    public abstract function style ($renderer = true, $build = true);

}