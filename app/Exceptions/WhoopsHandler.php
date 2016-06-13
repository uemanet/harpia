<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Response;
use App\Exceptions\Handler as BaseExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WhoopsHandler extends BaseExceptionHandler
{


    /**
     * @param \Illuminate\Http\Request $request
     * @param Exception $e
     * @return Response|\Symfony\Component\HttpFoundation\Response
     * @throws Exception
     */
    public function render($request, Exception $e)
    {
        if ($this->isHttpException($e)) {
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

    /**
     * Render an exception using Whoops.
     *
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    protected function renderExceptionWithWhoops(Exception $e)
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());

        return new \Illuminate\Http\Response(
            $whoops->handleException($e),
            $e->getStatusCode(),
            $e->getHeaders()
        );
    }
}
