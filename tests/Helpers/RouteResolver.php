<?php

namespace Tests\Helpers;

/**
 * Classe para Mock de RouteResolver
 * @codeCoverageIgnore
 * @see \Illuminate\Http\Request::getRouteResolver()
 * @see \Illuminate\Http\Request::setRouteResolver()
 */
class RouteResolver
{
    protected $name;

    protected $permissions;

    public function __construct($routeName)
    {
        $this->name = $routeName;
        $this->permissions = null;
    }

    public function getName()
    {
        if ($this->permissions) {
            return $this->permissions[array_rand($this->permissions)];
        }

        return $this->name;
    }

    public function setPermissions(array $permissions)
    {
        $this->permissions = $permissions;
    }
}
