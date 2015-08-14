<?php namespace App\Libraries\Assets\Tasks\Css;

use App\Libraries\Assets\Asset;
use App\Libraries\Assets\Collection;
use League\Pipeline\StageInterface;

/**
 * Class Min
 *
 *
 *
 * @package App\Libraries\Assets\Tasks\Css
 * @author  LAHAXE Arnaud
 */
class Min implements StageInterface
{

    /**
     * @param Collection $collection
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
    public function process ($collection)
    {
        \Log::info('Assets::Css::Min on collection ' . $collection->getCollectionId());

        $newAssets = [];
        foreach ($collection->getType(Asset::CSS) as $asset) {
            $outputFile = $collection->getTmpDirectory() . DIRECTORY_SEPARATOR . md5($asset->getPath()) . '.min.css';
            $minified   = \CssMin::minify(file_get_contents($asset->getPath()), [
                "ImportImports"                 => FALSE,
                "RemoveComments"                => TRUE,
                "RemoveEmptyRulesets"           => TRUE,
                "RemoveEmptyAtBlocks"           => TRUE,
                "ConvertLevel3AtKeyframes"      => FALSE,
                "ConvertLevel3Properties"       => FALSE,
                "Variables"                     => TRUE,
                "RemoveLastDelarationSemiColon" => FALSE,
                'relativePath'                  => '/assets/css/'
            ]);
            file_put_contents($outputFile, $minified);
            $newAssets[] = new Asset(Asset::CSS, $outputFile);
        }

        $collection->setType(Asset::CSS, $newAssets);

        return $collection;
    }
}