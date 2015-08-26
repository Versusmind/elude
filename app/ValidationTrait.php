<?php namespace App;


use Illuminate\Support\MessageBag;

trait ValidationTrait
{
    /**
     * @var MessageBag
     */
    public $errors;

    /**
     * @return array
     */
    public function getRules()
    {
        return isset(self::$rules)?self::$rules:[];
    }
}