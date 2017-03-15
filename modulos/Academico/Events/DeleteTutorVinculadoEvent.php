<?php
/**
 * Created by PhpStorm.
 * User: lvieira
 * Date: 06/03/17
 * Time: 16:52
 */

namespace Modulos\Academico\Events;

use Harpia\Event\Event;
use Modulos\Core\Model\BaseModel;

class DeleteTutorVinculadoEvent extends Event
{
    public function __construct(BaseModel $entry, $action = "DELETE")
    {
        parent::__construct($entry, $action);
    }
}
