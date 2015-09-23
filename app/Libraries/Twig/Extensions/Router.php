<?php namespace App\Libraries\Twig\Extensions;

use Twig_Extension;
use Twig_SimpleFunction;

class Router extends Twig_Extension
{

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'myo2_twig_extension_router';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction(
                'route',
                function ($name, $parameters = []) {
                    return route($name, $parameters);
                }
            )
        ];
    }
}