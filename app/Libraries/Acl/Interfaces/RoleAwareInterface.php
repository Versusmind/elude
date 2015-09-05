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
 * @file RoleAwareInterface.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description RoleAwareInterface
 *
 ******************************************************************************/

use App\Role;

interface RoleAwareInterface
{

    public function hasRole(GrantableInterface $grantable, Role $role);

    public function addRole(GrantableInterface $grantable, Role $role);

    public function removeRole(GrantableInterface $grantable, Role $role);

}
