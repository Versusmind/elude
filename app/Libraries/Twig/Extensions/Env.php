<?php namespace App\Libraries\Twig\Extensions;

use Twig_Extension;
use Twig_SimpleFunction;

class Env extends Twig_Extension
{

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'myo2_twig_extension_env';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction(
                'env',
                function ($name, $default = null) {
                    return env($name, $default);
                }
            )
        ];
    }
}