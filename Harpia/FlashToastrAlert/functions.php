<?php

if (! function_exists('flash')) {
    function flash($message = null)
    {
        $notifier = app('flash');

        if (! is_null($message)) {
            return $notifier->info($message);
        }

        return $notifier;
    }
}
