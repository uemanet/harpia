<?php

namespace Harpia\Mock;

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
            return array_random($this->permissions);
        }

        return $this->name;
    }

    public function setPermissions(array $permissions)
    {
        $this->permissions = $permissions;
    }
}
