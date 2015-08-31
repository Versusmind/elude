<?php namespace App\Libraries\Acl\Interfaces;

interface UserRestrictionCapabilitiesInterface extends UserRestrictionInterface
{

    public function can($stringPermission);

    public function cannot($stringPermission);

    public function canUse(\App\Libraries\Acl\Interfaces\UserRestrictionInterface $model, array $parameters = []);

    public function cannotUse(\App\Libraries\Acl\Interfaces\UserRestrictionInterface $model, array $parameters = []);

}