<?php namespace App\Libraries\OAuth\Grant;

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
 * @last-edited 05/09/2015
 * @description Permission
 *
 ******************************************************************************/

use Illuminate\Support\Facades\Session;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;

/**
 * Class Password
 * @package Libraries\OAuth\Grant
 */
class Password extends PasswordGrant
{
    /**
     * @return array
     * @throws \League\OAuth2\Server\Exception\InvalidClientException
     * @throws \League\OAuth2\Server\Exception\InvalidRefreshException
     * @throws \League\OAuth2\Server\Exception\InvalidRequestException
     * @throws \League\OAuth2\Server\Exception\InvalidScopeException
     */
    public function completeFlow()
    {
        $response = parent::completeFlow();

        // update user oauth token in session
        Session::put('oauth', $response);

        return $response;
    }

}