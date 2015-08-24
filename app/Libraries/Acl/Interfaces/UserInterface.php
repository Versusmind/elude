<?php namespace Libraries\Acl\Interfaces;


/**
 * Interface UserInterface
 *
 * @package Libraries\Acl\Interfaces
 */
interface UserInterface extends GrantableInterface
{
    public function roles();

    public function group();
}
