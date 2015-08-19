<?php
/**
 * Created by PhpStorm.
 * User: arnaud
 * Date: 19/08/2015
 * Time: 21:25
 */
namespace App\Libraries\Assets;

class BuilderDetector
{
    /**
     * @param Collection $assets
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     *
     * @return bool
     */
    public function isBuildNeeded(Collection $assets)
    {
        if (!is_file($assets->versionFilePath())) {
            return true;
        }

        // get version detail
        $build = json_decode(file_get_contents($assets->versionFilePath()));

        // not a valid js we rebuild
        if ($build === false) {
            return true;
        }

        // does the change concat option change since this version ?
        if ($build->concat !== config('assets.concat')) {
            return true;
        }

        // no need to re-test in production, files must not change
        if (\App::environment() !== 'production') {

            // check if files change since last version
            foreach ($assets->getAssets() as $types) {
                foreach ($types as $file) {
                    if (filemtime($file->getPath()) > $build->time) {
                        return true;
                    }
                }
            }
        }

        // try to reload the build version
        if ($this->reloadFromBuild($assets, $build)) {
            return false;
        }

        return true;
    }

    /**
     * @param Collection $assets
     * @param $build
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     *
     * @return bool
     */
    protected function reloadFromBuild(Collection $assets, $build)
    {
        \Log::info('Assets::Reload reload build for collection ' . $assets->getCollectionId());

        // if one file of the collection build is missing, we need to rebuild
        foreach ($build->build as $buildFile) {
            if (!file_exists($buildFile->path)) {
                return false;
            }
        }

        // reset assets collections to reload builded files
        $assets->setAssets([]);
        foreach ($build->build as $buildFile) {
            $assets->appendType($buildFile->type, new Asset($buildFile->type, $buildFile->path));
        }

        return true;
    }

    /**
     * @param Collection $collection
     * @param array $except
     *
     * @return array
     */
    public function getBuildNeeded(Collection $collection, array $except = array())
    {
        $buildNeeded = [];
        foreach (Collection::$types as $type) {
            if ($collection->hasType($type) && !in_array($type, $except, true)) {
                $buildNeeded[] = Orchestrator::$buildType[$type];
            }
        }

        return array_unique($buildNeeded);
    }
}
