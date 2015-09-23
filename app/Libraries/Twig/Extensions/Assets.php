<?php namespace App\Libraries\Twig\Extensions;

use Twig_Extension;
use Twig_SimpleFunction;

class Assets extends Twig_Extension
{

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'myo2_twig_extension_assets';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction(
                'style',
                function ($name) {
                    return \App\Facades\Assets::style(\App\Libraries\Assets\Collection::createByGroup($name));
                },
                [
                    'is_safe' => ['html']
                ]
            ),
            new Twig_SimpleFunction(
                'script',
                function ($name) {
                    return \App\Facades\Assets::script(\App\Libraries\Assets\Collection::createByGroup($name));
                }
            )
        ];
    }
}