<?php namespace App\Libraries\Acl\Traits;

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
 * @file UserRestrictionCapabilities.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description UserRestrictionCapabilities
 *
 ******************************************************************************/


trait UserRestrictionCapabilities
{

    /**
     * @param $stringPermission
     *
     * @return mixed
     */
    public function can($stringPermission)
    {
        if ($stringPermission instanceof \App\Permission) {
            $stringPermission = $stringPermission->getAction();
        }

        return \App\Facades\Acl::isUserAllow($this, $stringPermission);
    }

    /**
     * @param $stringPermission
     *
     * @return mixed
     */
    public function cannot($stringPermission)
    {
        return !$this->can($stringPermission);
    }

    /**
     * @param \App\Libraries\Acl\Interfaces\UserRestrictionInterface $model
     * @param array                                                  $parameters
     *
     * @return mixed
     */
    public function canUse(\App\Libraries\Acl\Interfaces\UserRestrictionInterface $model, array $parameters = [])
    {

        return $model->isUserAllow($this, $parameters);
    }

    /**
     * @param \App\Libraries\Acl\Interfaces\UserRestrictionInterface $model
     * @param array                                                  $parameters
     *
     * @return bool
     */
    public function cannotUse(\App\Libraries\Acl\Interfaces\UserRestrictionInterface $model, array $parameters = [])
    {
        return !$this->canUse($model, $parameters);
    }
}