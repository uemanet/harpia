<?php

namespace Modulos\Seguranca\Providers\ActionButton;

use Modulos\Seguranca\Providers\Seguranca\Seguranca;

class ActionButton
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function render($buttons)
    {
        $render = '';
        $seguranca = $this->app[Seguranca::class];

        foreach ($buttons as $key => $button) {
            if (!env('IS_SECURITY_ENNABLED') || $seguranca->haspermission($button->getRoute())) {
                $render .= '<a href="' . route($button->getRoute(), $button->getParameters()) . '" target="' . $button->getTarget() . '" class="' . $button->getStyle() . '"> <i class="' . $button->getIcon() . '"></i> ' . $button->getName() . '</a>';
            }
        }

        return $render;
    }

    public function grid($component)
    {
        switch ($component['type']) {
            case 'SELECT':
                return $this->renderButtonGridSelect($component['config'], $component['buttons']);
            case 'BUTTONS':
                return $this->renderButtonGrid($component['config'], $component['buttons']);
            case 'LINE':
                return $this->renderButtonGridLine($component['buttons']);
        }
    }

    private function renderButtonGridSelect($config, $buttons)
    {
        $seguranca = $this->app[Seguranca::class];
        $flag = 0;

        // TRADUTOR: Converte a classe legada btn-default para o padrão do BS5
        $classButton = str_replace('btn-default', 'btn-outline-secondary btn-sm', $config['classButton']);

        // Cria o Split Button padrão do Bootstrap 5
        $render = '<div class="btn-group">';
        $render .= '<button type="button" class="btn ' . $classButton . '">' . $config['label'] . '</button>';
        $render .= '<button type="button" class="btn ' . $classButton . ' dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">';
        $render .= '<span class="visually-hidden">Toggle Dropdown</span>';
        $render .= '</button>';

        if (!empty($buttons)) {
            // Garante que o menu abra alinhado à direita para não criar scroll horizontal
            $render .= '<ul class="dropdown-menu dropdown-menu-end" role="menu">';

            foreach ($buttons as $key => $button) {
                $rota = $button['route'];
                $parameters = isset($button['parameters']) ? $button['parameters'] : [];

                if (!env('IS_SECURITY_ENNABLED') || $seguranca->haspermission($rota)) {
                    $flag += 1;

                    // TRADUTOR: Ajusta cores legadas (ex: text-red vira text-danger)
                    $itemClass = isset($button['classButton']) ? $button['classButton'] : '';
                    $itemClass = str_replace('text-red', 'text-danger', $itemClass);

                    if ($button['method'] == 'get') {
                        $render .= '<li>';
                        // Adiciona a classe dropdown-item obrigatória no BS5
                        $render .= '<a href="' . route($rota, $parameters) . '" class="dropdown-item ' . $itemClass . '"';

                        if (isset($button['id'])) {
                            $render .= ' id="' . $button['id'] . '"';
                        }

                        $render .= '>';
                        $render .= '<i class="' . $button['icon'] . '"></i> ' . $button['label'];
                        $render .= '</a></li>';

                        continue;
                    }

                    // Formulário para botões de POST/DELETE (Excluir)
                    $render .= '<li>';
                    $render .= '<form action="' . route($rota, $parameters) . '" method="' . strtoupper($button['method']) . '" class="form-singlebutton m-0">';
                    $render .= '<input type="hidden" name="id" value="' . $button['id'] . '">';
                    $render .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                    $render .= '<input type="hidden" name="_method" value="' . strtoupper($button['method']) . '">';
                    // Transforma o botão submit em um dropdown-item nativo
                    $render .= '<button type="submit" class="dropdown-item ' . $itemClass . '"><i class="' . $button['icon'] . '"></i> ' . $button['label'] . '</button>';
                    $render .= '</form></li>';
                }
            }
            $render .= '</ul></div>';

            if ($flag == 0) {
                $render = '';
            }
        }

        return $render;
    }

    private function renderButtonGrid($config, $buttons)
    {
        $seguranca = $this->app[Seguranca::class];
        $render = '';

        foreach ($buttons as $key => $button) {
            $rota = $button['route'];
            $parameters = isset($button['parameters']) ? $button['parameters'] : [];

            if (!env('IS_SECURITY_ENNABLED') || $seguranca->haspermission($rota)) {

                // TRADUTOR
                $btnClass = str_replace('btn-default', 'btn-outline-secondary btn-sm', $button['classButton']);

                if ($config['showLabel']) {
                    $render .= '<a style="margin-right:5px" href="' . route($rota, $parameters) . '" 
                                class="btn ' . $btnClass . '">';
                    $render .= '<i class="' . $button['icon'] . '"></i> ' . $button['label'] . '</a>';
                    continue;
                }

                $render .= '<a style="margin-right:5px" title="' . $button['label'] . '" 
                            href="' . route($rota, $parameters) . '" 
                            class="btn ' . $btnClass . '">';
                $render .= '<i class="' . $button['icon'] . '"></i></a>';
            }
        }

        return $render;
    }

    private function renderButtonGridLine($buttons)
    {
        $seguranca = $this->app[Seguranca::class];
        $flag = 0;
        $render = '';

        if (!empty($buttons)) {
            // Remove as bordas dessa sub-tabela para não conflitar com a tabela principal do BS5
            $render .= '<table class="m-0 border-0"><tbody><tr>';

            foreach ($buttons as $key => $button) {
                $rota = $button['route'];
                $parameters = isset($button['parameters']) ? $button['parameters'] : [];

                if (!env('IS_SECURITY_ENNABLED') || $seguranca->haspermission($rota)) {
                    $flag += 1;

                    // TRADUTOR
                    $btnClass = str_replace('btn-default', 'btn-outline-secondary btn-sm', $button['classButton']);

                    if ($button['method'] == 'get') {
                        $render .= '<td style="padding-right: 5px; border: none;">';
                        $render .= '<div class="btn-group">';
                        $render .= '<a href="' . route($rota, $parameters) . '" class="btn ' . $btnClass . '" ';

                        if (array_key_exists('attributes', $button)) {
                            foreach ($button['attributes'] as $attr => $value) {
                                $render .= $attr . '="' . $value . '" ';
                            }
                        }

                        $render .= '>';
                        $render .= '<i class="' . $button['icon'] . '"></i> ' . $button['label'];
                        $render .= '</a></div>';
                        $render .= '</td>';

                        continue;
                    }

                    $render .= '<td style="padding-right: 5px; border: none;">';
                    $render .= '<div class="btn-group">';
                    $render .= '<form action="' . route($rota, $parameters) . '" method="' . strtoupper($button['method']) . '" class="form-linebutton m-0">';
                    $render .= '<input type="hidden" name="id" value="' . $button['id'] . '">';
                    $render .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                    $render .= '<input type="hidden" name="_method" value="' . strtoupper($button['method']) . '">';
                    $render .= '<button type="submit" class="btn ' . $btnClass . '"><i class="' . $button['icon'] . '"></i> ' . $button['label'] . '</button>';
                    $render .= '</form></div>';
                    $render .= '</td>';
                }
            }
            $render .= '</tr></tbody></table>';

            if ($flag == 0) {
                $render = '';
            }
        }

        return $render;
    }
}