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

        if (!$notifications || empty($notifications)) {
            return '';
        }

        $defaultConfig = $this->config->get('flashtoastralert.options', []);

        // Sanitiza os dados da mesma forma que o pacote original fazia
        $safeNotifications = array_map(function($notif) {
            return [
                'type' => $notif['type'],
                'title' => isset($notif['title']) ? htmlentities($notif['title']) : null,
                'message' => str_replace(['&lt;', '&gt;'], ['<', '>'], e($notif['message'])),
                'options' => $notif['options']
            ];
        }, $notifications);

        $payload = [
            'defaultConfig' => $defaultConfig,
            'notifications' => $safeNotifications
        ];

        // Retorna apenas um objeto de DADOS, e não funções executáveis.
        // O JS vai pegar isso e processar no momento certo!
        return "<script>\n" .
            "    window.HarpiaFlashMessages = " . json_encode($payload) . ";\n" .
            "</script>";
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