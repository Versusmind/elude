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
 * @file Pipeline.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description Pipeline
 *
 ******************************************************************************/

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
     * @return \League\Pipeline\Pipeline
     */
    public function template ()
    {
        return (new PipelineBuilder)->add(new Copy(Asset::TEMPLATE))
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