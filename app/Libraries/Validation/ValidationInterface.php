<?php namespace App\Libraries\Validation;
use Illuminate\Support\MessageBag;

/******************************************************************************
 *
 * @package Myo 2
 * @copyright © 2015 by Versusmind.
 * All rights reserved. No part of this document may be
 * reproduced or transmitted in any form or by any means,
 * electronic, mechanical, photocopying, recording, or
 * otherwise, without prior written permission of Versusmind.
 * @link http://www.versusmind.eu/
 *
 * @file ValidationInterface.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description ValidationInterface
 *
 ******************************************************************************/


interface ValidationInterface
{
    public function getRules();

    public function getHidden();

    public function toArray();

    /**
     * @return MessageBag
     */
    public function getErrors();

    public function setErrors(MessageBag $errors);
}