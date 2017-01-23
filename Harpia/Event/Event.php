<?php

namespace Harpia\Event;

use App\Models\BaseModel;

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
    protected $registro;

    /**
     * Mensagem
     * @var string
     */
    protected $message;

    /**
     * Informacao extra sobre o evento
     * @var string
     */
    protected $extraInformation;

    /**
     * Event constructor.
     * Recebe um objeto model associado ao evento
     * @param BaseModel $registro
     */
    public function __construct(BaseModel $registro, $message = null, $extraInformation = null)
    {
        $this->message = $message;
        $this->extraInformation = $extraInformation;
        $this->registro = $registro;
    }

    /**
     * @return BaseModel
     * @see EventInterface::getData()
     */
    public function getData()
    {
        return $this->registro;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getExtraInformation()
    {
        return $this->extraInformation;
    }
}
