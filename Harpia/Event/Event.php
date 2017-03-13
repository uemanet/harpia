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

    /**
     * Event constructor.
     * Recebe um objeto model associado ao evento
     * @param BaseModel $entry
     */
    public function __construct(BaseModel $entry, $action)
    {
        $this->entry = $entry;
        $this->action = $action;
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
}
