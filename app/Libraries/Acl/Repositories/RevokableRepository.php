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
 * @file RevokableRepository.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description RevokableRepository
 *
 ******************************************************************************/

use App\Libraries\Acl\Interfaces\PermissionInterface;
use App\Libraries\Acl\Interfaces\RevokableInterface;

abstract class RevokableRepository extends GrantableRepository
{

    /**
     * Permission constructor.
     */
    public function __construct($className)
    {
        parent::__construct($className);
    }

    /**
     * @param RevokableInterface  $model
     * @param PermissionInterface $permission
     */
    public function revoke(RevokableInterface $model, PermissionInterface $permission)
    {
        throw new \RuntimeException('Not implemented yet');
    }
}