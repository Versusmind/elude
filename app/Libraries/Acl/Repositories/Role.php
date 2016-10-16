<?php namespace App\Libraries\Acl\Repositories;

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
 * @file Role.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description Role
 *
 ******************************************************************************/

class Role extends RevokableRepository
{

    /**
     * Permission constructor.
     */
    public function __construct()
    {
        parent::__construct(\App\Role::class);
    }
}