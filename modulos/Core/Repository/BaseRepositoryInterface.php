<?php

namespace Modulos\Core\Repository;

interface BaseRepositoryInterface
{
    public function paginateRequest(array $requestParams);
}
