<?php namespace App\Libraries\Twig\Extensions;

use Twig_Extension;
use Twig_SimpleFunction;

class Auth extends Twig_Extension
{

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'myo2_twig_extension_auth';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction(
                'user',
                function () {
                    return \Illuminate\Support\Facades\Auth::user();
                }
            )
        ];
    }
}