<?php namespace App\Libraries\Twig\Extensions;

use Twig_Extension;
use Twig_SimpleFunction;

class Utils extends Twig_Extension
{

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'myo2_twig_extension_utils';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('base64_encode', 'base64_encode'),
        ];
    }
}