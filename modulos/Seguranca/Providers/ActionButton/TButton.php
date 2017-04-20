<?php

namespace Modulos\Seguranca\Providers\ActionButton;

class TButton
{
    protected $name;
    protected $route;
    protected $icon;
    protected $style;
    protected $parameters = [];
    protected $target = '_self';

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setStyle($style)
    {
        $this->style = $style;
        return $this;
    }

    public function getStyle()
    {
        return $this->style;
    }

    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }

    public function getTarget()
    {
        return $this->target;
    }
}
