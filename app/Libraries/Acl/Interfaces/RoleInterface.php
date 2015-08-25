<?php namespace Libraries\Acl\Interfaces;


/**
 * Interface RoleInterface
 *
 * @package Libraries\Acl\Interfaces
 */
interface RoleInterface extends GrantableInterface
{

    public function getFilter();
}
