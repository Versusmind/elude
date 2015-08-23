<?php namespace App\Libraries\Assets\Pipeline;

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
 * @file Production.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description Production
 *
 ******************************************************************************/

use App\Libraries\Assets\Asset;
use App\Libraries\Assets\Tasks\Cleaner;
use App\Libraries\Assets\Tasks\Concat;
use App\Libraries\Assets\Tasks\Css\Html as CssRenderer;
use App\Libraries\Assets\Tasks\Css\Min as CssMin;
use App\Libraries\Assets\Tasks\Css\Rewrite;
use App\Libraries\Assets\Tasks\Javascript\Html as JsRenderer;
use App\Libraries\Assets\Tasks\Javascript\Min as JsMin;
use App\Libraries\Assets\Tasks\Less\Compile as LessCompiler;
use App\Libraries\Assets\Tasks\Sass\Compile as SassCompiler;
use App\Libraries\Assets\Tasks\Version;
use League\Pipeline\PipelineBuilder;

/**
 * Class Production
 *
 * @package App\Libraries\Assets\Pipeline
 */
class Production extends Pipeline
{
    /**
     * @param bool|true $renderer
     * @param bool|true $build
     *
     * @return \League\Pipeline\Pipeline
     */
    public function javascript($renderer = true, $build = true)
    {
        $pipelineBuilder = new PipelineBuilder;

        if ($build) {
            $pipelineBuilder->add(new Concat(Asset::JS))
                ->add(new JsMin())
                ->add(new Version(Asset::JS))
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
     *
     * @return \League\Pipeline\Pipeline
     */
    public function style($renderer = true, $build = true)
    {
        $pipelineBuilder = new PipelineBuilder;

        if ($build) {
            $pipelineBuilder->add(new SassCompiler())
                ->add(new LessCompiler())
                ->add(new Concat(Asset::CSS))
                ->add(new CssMin())
                ->add(new Version(Asset::CSS))
                ->add(new Cleaner());
        }

        if ($renderer) {
            $pipelineBuilder->add(new CssRenderer());
        }

        return $pipelineBuilder->build();
    }
}
