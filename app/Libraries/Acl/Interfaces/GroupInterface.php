<?php namespace App\Libraries\Acl\Interfaces;

/**
 * Interface GroupInterface
 *
 * @package Libraries\Acl\Interfaces
 */
interface GroupInterface extends GrantableInterface
{

    public function roles();
}
