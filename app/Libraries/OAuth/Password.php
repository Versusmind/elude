<?php namespace App\Libraries\OAuth;

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
 * @file Password.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description Username/Password authentication for OAuth server
 *
 ******************************************************************************/

class Password
{
    public function verify($username, $password)
    {

        $credentials = [
            'username'    => $username,
            'password' => $password,
        ];

        if (\Auth::attempt($credentials)) {
            return \Auth::user()->id;
        }

        return false;
    }
}