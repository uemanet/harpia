<?php

namespace App\Exceptions;

use App\Exceptions\Handler as BaseExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class WhoopsHandler extends BaseExceptionHandler
{


    public function render($request, \Throwable $e)
    {
        if ($e instanceof HttpExceptionInterface) {
            return $this->renderHttpException($e);
        }

        if ($this->shouldntReport($e)) {
            throw $e;
        }


        if (config('app.debug')) {
            return $this->renderExceptionWithWhoops($e);
        }

        return parent::render($request, $e);
    }

    protected function renderExceptionWithWhoops(\Throwable $e)
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());

        $statusCode = 500;
        $headers = [];
        if ($e instanceof HttpExceptionInterface) {
            $statusCode = $e->getStatusCode();
            $headers = $e->getHeaders();
        }

        return new \Illuminate\Http\Response(
            $whoops->handleException($e),
            $statusCode,
            $headers
        );
    }
}
