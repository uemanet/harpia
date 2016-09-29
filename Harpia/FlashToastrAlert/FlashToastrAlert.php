<?php

namespace Harpia\FlashToastrAlert;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Session\SessionManager;

class FlashToastrAlert
{
    protected $notifications = [];

    protected $session;

    protected $config;

    public function __construct(SessionManager $session, Repository $config)
    {
        $this->session = $session;
        $this->config = $config;
    }

    public function render()
    {
        $notifications = $this->session->get('flashtoastralert_notifications');

        if (!$notifications) {
            $notifications = [];
        }

        $output = '<script type="text/javascript">';

        $lastConfig = [];

        foreach ($notifications as $notification) {
            $config = $this->config->get('flashtoastralert.options');

            if (count($notification['options']) > 0) {
                // Merge user supplied options with default options
                $config = array_merge($config, $notification['options']);
            }

            // Config persists between toasts
            if ($config != $lastConfig) {
                $output .= 'toastr.options = ' . json_encode($config) . ';';

                $lastConfig = $config;
            }

            // Toastr output
            $output .= 'toastr.' . $notification['type'] . "('" . str_replace("'", "\\'", str_replace(['&lt;', '&gt;'], ['<', '>'], e($notification['message']))) . "'" . (isset($notification['title']) ? ", '" . str_replace("'", "\\'", htmlentities($notification['title'])) . "'" : null) . ');';
        }

        $output .= '</script>';

        return $output;
    }

    public function add($type, $message, $title = null, $options = [])
    {
        $allowedTypes = ['error', 'info', 'success', 'warning'];

        if (!in_array($type, $allowedTypes)) {
            return false;
        }

        $this->notifications[] = [
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'options' => $options
        ];

        $this->session->flash('flashtoastralert_notifications', $this->notifications);
    }

    public function info($message, $title = null, $options = [])
    {
        $this->add('info', $message, $title, $options);
    }

    public function error($message, $title = null, $options = [])
    {
        $this->add('error', $message, $title, $options);
    }

    public function warning($message, $title = null, $options = [])
    {
        $this->add('warning', $message, $title, $options);
    }

    public function success($message, $title = null, $options = [])
    {
        $this->add('success', $message, $title, $options);
    }

    public function clear()
    {
        $this->notifications = [];
    }
}
