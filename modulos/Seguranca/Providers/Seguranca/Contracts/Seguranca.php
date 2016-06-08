<?php

namespace Modulos\Seguranca\Providers\Seguranca\Contracts;

/**
 * Interface Seguranca
 */
interface Seguranca
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
