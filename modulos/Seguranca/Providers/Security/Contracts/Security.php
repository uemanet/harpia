<?php

namespace Modulos\Seguranca\Providers\Security\Contracts;

/**
 * Interface Security
 */
interface Security
{
    /**
     * Get the current authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getUser();

    /**
     * Check if the authenticated user has the given permission.
     *
     * @param string $permission
     *
     * @return bool
     */
    public function haspermission($permission);
}
