<?php namespace App\Facades;

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
 * @file Assets.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description Assets
 *
 ******************************************************************************/


use Illuminate\Support\Facades\Facade;

class Assets extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        
        return 'assets';
    }
}