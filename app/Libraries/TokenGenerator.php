<?php namespace App\Libraries;

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
 * @file TokenGenerator.php
 * @author LAHAXE Arnaud
 * @last-edited 20/09/15
 * @description TokenGenerator
 *
 ******************************************************************************/


class TokenGenerator
{

    public function generate($length = 32)
    {

        // php 7 function to generate random string
        if (function_exists('random_bytes')) {
            $token = str_replace('=', '', base64_encode(random_bytes(80)));

        // if openssl extension is available
        }elseif (function_exists('openssl_random_pseudo_bytes')) {
            $token = str_replace('=', '', base64_encode(openssl_random_pseudo_bytes(80)));
        // fallback
        } else {
            // in other case we generate manually
            $token = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-', mt_rand(1, 20))), 1, 80);
        }
        if (strlen($token) < $length) {
            $token = str_repeat($token, floor($length / strlen($token)));

            $token = str_shuffle($token);
        }

        return substr($token, 0, $length - 1);
    }
}