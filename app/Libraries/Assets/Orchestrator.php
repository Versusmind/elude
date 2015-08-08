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
    /**
     * @param Collection $assets
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     */
    protected function initialize (Collection $assets)
    {
        $assets->setTmpDirectory(storage_path('tmp' . DIRECTORY_SEPARATOR . $assets->getCollectionId()));
        $assets->initializeFolder();
    }

    /**
     * @param Collection $assets
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
    public function font (Collection $assets)
    {
        \Log::info('Assets::Build start font build for collection ' . $assets->getCollectionId());

        return (new PipelineBuilder)->add(new Tasks\Copy(Asset::IMG))
            ->add(new Tasks\Version(Asset::FONT))
            ->build()
            ->process($assets);
    }

    /**
     * @param Collection $assets
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
    public function image (Collection $assets)
    {
        \Log::info('Assets::Build start image build for collection ' . $assets->getCollectionId());

        return (new PipelineBuilder)->add(new Tasks\Copy(Asset::IMG))
            ->add(new Tasks\Version(Asset::IMG))
            ->build()
            ->process($assets);
    }

    /**
     * @param Collection $assets
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
    public function javascript (Collection $assets)
    {
        $pipelineBuilder = new PipelineBuilder;
        if ($this->isBuildNeeded($assets)) {
            \Log::info('Assets::Build start javascript build for collection ' . $assets->getCollectionId());

            $this->initialize($assets);
            if (!env('ASSETS_CONCAT')) {
                $pipelineBuilder->add(new Tasks\Copy(Asset::JS));
            } else {
                $pipelineBuilder->add(new Tasks\Concat(Asset::JS))
                    ->add(new Tasks\Javascript\Min)
                    ->add(new Tasks\Version(Asset::JS));
            }

            $pipelineBuilder->add(new Tasks\Cleaner);
        }

        return $pipelineBuilder->add(new Tasks\Javascript\Html)->build()->process($assets);
    }

    /**
     * @param Collection $assets
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
    public function style (Collection $assets)
    {
        $pipelineBuilder = new PipelineBuilder;

        if ($this->isBuildNeeded($assets)) {
            \Log::info('Assets::Build start style build for collection ' . $assets->getCollectionId());

            $this->initialize($assets);

            $pipelineBuilder->add(new Tasks\Sass\Compile)
                ->add(new Tasks\Less\Compile);

            if (!env('ASSETS_CONCAT')) {
                $pipelineBuilder->add(new Tasks\Copy(Asset::CSS));
            } else {
                $pipelineBuilder
                    ->add(new Tasks\Concat(Asset::CSS))
                    ->add(new Tasks\Css\Min)
                    ->add(new Tasks\Version(Asset::CSS));
            }

            $pipelineBuilder->add(new Tasks\Cleaner);
        }

        return $pipelineBuilder->add(new Tasks\Css\Html)->build()->process($assets);
    }

    /**
     * @param Collection $assets
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return bool
     */
    public function isBuildNeeded (Collection $assets)
    {
        if (!is_file($assets->versionFilePath())) {
            return TRUE;
        }

        // get version detail
        $build = json_decode(file_get_contents($assets->versionFilePath()));
        if ($build === FALSE) {
            return TRUE;
        }

        // change concat option
        if ($build->concat !== env('ASSETS_CONCAT')) {
            return TRUE;
        }

        // no need to re-test in production, files must not change
        if (\App::environment() !== 'production') {

            // check if files change since last version
            foreach ($assets->getAssets() as $types) {
                foreach ($types as $file) {
                    if (filemtime($file->getPath()) > $build->time) {
                        return TRUE;
                    }
                }
            }
        }

        if ($this->reloadFromBuild($assets, $build)) {

            return FALSE;
        }

        return TRUE;
    }

    /**
     * @param Collection $assets
     * @param $build
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return bool
     */
    protected function reloadFromBuild (Collection $assets, $build)
    {
        \Log::info('Assets::Reload reload build for collection ' . $assets->getCollectionId());

        foreach ($build->build as $buildFile) {
            if(!file_exists($buildFile->path)) {
                return false;
            }
        }

        $assets->setAssets([]);
        foreach ($build->build as $buildFile) {
            $assets->appendType($buildFile->type, new Asset($buildFile->type, $buildFile->path));
        }

        return true;
    }
}