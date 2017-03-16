<?php

namespace Harpia\Event;

use Modulos\Core\Model\BaseModel;

/**
 * Class Event
 * @package Harpia\Event
 */
abstract class Event implements EventInterface
{
    /**
     * Registro da base de dados relacionado ao evento
     * @var BaseModel
     */
    protected $entry;
    protected $action;
    protected $extra;

    /**
     * Event constructor.
     * Recebe um objeto model associado ao evento
     * @param BaseModel $entry
     */
    public function __construct(BaseModel $entry, $action, $extra = null)
    {
        $this->entry = $entry;
        $this->action = $action;
        $this->extra = $extra;
    }

    /**
     * @return BaseModel
     * @see EventInterface::getData()
     */
    public function getData()
    {
        return $this->entry;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getExtra()
    {
        return $this->extra;
    }
}
