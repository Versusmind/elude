<?php namespace App\Libraries\Acl\Traits;

/**
 * User: LAHAXE Arnaud
 * Date: 31/08/2015
 * Time: 14:35
 * FileName : UserRestrictionCapabilities.php
 * Project : myo2
 */
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