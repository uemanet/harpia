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

        $render = '<div class="btn-group">';
        $render .= '<button type="button" class="btn ' . $config['classButton'] . '">' . $config['label'] . '</button>';
        $render .= '<button type="button" class="btn ' . $config['classButton'] . ' dropdown-toggle" data-toggle="dropdown" aria-expanded="false">';
        $render .= '<span class="caret"></span>';
        $render .= '<span class="sr-only"></span>';
        $render .= '</button>';

        if (!empty($buttons)) {
            $render .= '<ul class="dropdown-menu" role="menu">';

            foreach ($buttons as $key => $button) {
                $rota = $button['route'];
                $parameters = isset($button['parameters']) ? $button['parameters'] : [];

                if (!env('IS_SECURITY_ENNABLED') || $seguranca->haspermission($rota)) {
                    $flag += 1;

                    if ($button['method'] == 'get') {
                        $render .= '<li>';
                        $render .= '<a href="' . route($rota, $parameters) . '" class="' . $button['classButton'] . '"';

                        if (isset($button['id'])) {
                            $render .= ' id="' . $button['id'] . '"';
                        }

                        $render .= '>';
                        $render .= '<i class="' . $button['icon'] . '"></i> ' . $button['label'];
                        $render .= '</a></li>';

                        continue;
                    }

                    $render .= '<li>';
                    $render .= '<form action="' . route($rota, $parameters) . '" method="' . strtoupper($button['method']) . '" class="form-singlebutton">';
                    $render .= '<input type="hidden" name="id" value="' . $button['id'] . '">';
                    $render .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                    $render .= '<input type="hidden" name="_method" value="' . strtoupper($button['method']) . '">';
                    $render .= '<button class="' . $button['classButton'] . '"><i class="' . $button['icon'] . '"></i> ' . $button['label'] . '</button>';
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
                if ($config['showLabel']) {
                    $render .= '<a style="margin-right:5px" href="' . route($rota, $parameters) . '" 
                                class="btn ' . $button['classButton'] . '">';
                    $render .= '<i class="' . $button['icon'] . '"></i> ' . $button['label'] . '</a>';
                    continue;
                }

                $render .= '<a style="margin-right:5px" title="' . $button['label'] . '" 
                            href="' . route($rota, $parameters) . '" 
                            class="btn ' . $button['classButton'] . '">';
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
            $render .= '<table><tbody><tr>';

            foreach ($buttons as $key => $button) {
                $rota = $button['route'];
                $parameters = isset($button['parameters']) ? $button['parameters'] : [];

                if (!env('IS_SECURITY_ENNABLED') || $seguranca->haspermission($rota)) {
                    $flag += 1;

                    if ($button['method'] == 'get') {
                        $render .= '<td style="padding-right: 5px">';
                        $render .= '<div class="btn-group">';
                        $render .= '<a href="' . route($rota, $parameters) . '" class="' . $button['classButton'] . '" ';

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

                    $render .= '<td style="padding-right: 5px">';
                    $render .= '<div class="btn-group">';
                    $render .= '<form action="' . route($rota, $parameters) . '" method="' . strtoupper($button['method']) . '" class="form-linebutton">';
                    $render .= '<input type="hidden" name="id" value="' . $button['id'] . '">';
                    $render .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                    $render .= '<input type="hidden" name="_method" value="' . strtoupper($button['method']) . '">';
                    $render .= '<button class="' . $button['classButton'] . '"><i class="' . $button['icon'] . '"></i> ' . $button['label'] . '</button>';
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
