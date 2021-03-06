<?php namespace App\Libraries\Acl\Interfaces;

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
 * @file UserRestrictionInterface.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description UserRestrictionInterface
 *
 ******************************************************************************/

use App\User;

interface UserRestrictionInterface
{

    /**
     * Test if the user given can edit the model
     *
     * @param \App\User $user
     * @param array     $parameters
     *
     * @return mixed
     */
    public function isUserAllow(User $user, array $parameters = []);

    /**
     * @return string
     */
    public function getUserIdFields();


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user();
}