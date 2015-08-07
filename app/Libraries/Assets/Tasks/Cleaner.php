<?php namespace App\Libraries\Assets\Tasks;

use App\Libraries\Assets\Collection;
use League\Pipeline\StageInterface;

/**
 * Class Version
 *
 * @author  LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * @package App\Libraries\Assets\Tasks
 */
class Cleaner implements StageInterface
{

    /**
     * @param Collection $collection
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return Collection|mixed
     */
    public function process ($collection)
    {
        \Log::info('Assets::Cleaner on collection ' . $collection->getCollectionId());

        self::deleteDir($collection->getTmpDirectory());

        return $collection;
    }

    public static function deleteDir ($dirPath)
    {
        if (!is_dir($dirPath)) {
            throw new \InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
}