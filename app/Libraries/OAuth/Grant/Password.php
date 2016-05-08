<?php namespace Libraries\OAuth\Grant;

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
 * @file RefreshToken.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description Permission
 *
 ******************************************************************************/

use Illuminate\Support\Facades\Session;
use League\OAuth2\Server\Grant\RefreshTokenGrant;

/**
 * Class RefreshToken
 * @package Libraries\OAuth\Grant
 */
class RefreshToken extends RefreshTokenGrant
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