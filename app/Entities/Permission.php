<?php

use LaravelDoctrine\ACL\Contracts\Permission as PermissionContract;

/**
 *
 */
class Permission extends Base\Permission implements PermissionContract
{
    /**
     * Instantiate a new Permission
     */
    public function __construct()
    {
        return parent::__construct();
    }
}
