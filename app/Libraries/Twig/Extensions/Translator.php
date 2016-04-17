<?php namespace App\Libraries\Twig\Extensions;

use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

/**
 * Class Translator
 * @package App\Libraries\Twig\Extensions
 */
class Translator extends Twig_Extension
{
    /**
     * @var \Illuminate\Translation\Translator
     */
    protected $translator;

    public function __construct()
    {
        $this->translator = app('translator');
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'myo2_twig_extension_translator';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('trans', [$this->translator, 'trans']),
            new Twig_SimpleFunction('trans_choice', [$this->translator, 'transChoice']),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter(
                'trans',
                [$this->translator, 'trans'],
                [
                    'pre_escape' => 'html',
                    'is_safe'    => ['html'],
                ]
            ),
            new Twig_SimpleFilter(
                'trans_choice',
                [$this->translator, 'transChoice'],
                [
                    'pre_escape' => 'html',
                    'is_safe'    => ['html'],
                ]
            ),
        ];
    }
}
