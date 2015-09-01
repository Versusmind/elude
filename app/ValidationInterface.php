<?php namespace App;


interface ValidationInterface
{
    public function getRules();

    public function getHidden();

    public function toArray();
}