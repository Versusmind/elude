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
 * @file UserRestrictionCapabilitiesInterface.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description UserRestrictionCapabilitiesInterface
 *
 ******************************************************************************/

interface UserRestrictionCapabilitiesInterface extends UserRestrictionInterface
{

    public function can($stringPermission);

    public function cannot($stringPermission);

    public function canUse(\App\Libraries\Acl\Interfaces\UserRestrictionInterface $model, array $parameters = []);

    public function cannotUse(\App\Libraries\Acl\Interfaces\UserRestrictionInterface $model, array $parameters = []);

    public function isSuperAdmin();
}