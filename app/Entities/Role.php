<?php

use Doctrine\Common\Collections\ArrayCollection;
use LaravelDoctrine\ACL\Permissions\HasPermissions;
use LaravelDoctrine\ACL\Contracts\HasPermissions as HasPermissionContract;

/**
 *
 */
class Role extends Base\Role implements HasPermissionContract
{
    use HasPermissions;

    /**
     * Instantiate a new Role
     */
    public function __construct()
    {
        return parent::__construct();
    }


    /**
     *
     */
    public function __toString()
    {
        return $this->getName();
    }
}
