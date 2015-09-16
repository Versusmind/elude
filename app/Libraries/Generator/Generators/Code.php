<?php namespace App\Libraries\Generator\Generators;

use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Created by PhpStorm.
 * User: arnaud
 * Date: 16/09/2015
 * Time: 21:41
 */
abstract class Code
{
    protected $parameters;

    protected $resolver;

    /**
     *
     */
    public function __construct(array $options)
    {
        $this->resolver = new OptionsResolver();

        $this->resolver->setRequired($this->options());
        //foreach($this->options() as $option) {
        //    $this->resolver->setRequired($option);
        //}

        $this->parameters = $this->resolver->resolve($options);
    }


    /**
     * @return string
     */
    public abstract function generate();

    /**
     * @return array
     */
    public abstract function options();

    /**
     * @param $name
     * @return mixed
     */
    public function get($name)
    {
        return $this->parameters[$name];
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function set($name, $value)
    {
        $this->parameters[$name] = $value;

        $this->parameters = $this->resolver->resolve($this->parameters);

        return $this;
    }
}