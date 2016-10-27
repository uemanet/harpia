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
            $rota = substr($button->getAction(), 1);

            if (!env('IS_SECURITY_ENNABLED') || $seguranca->haspermission($rota)) {
                $render .= '<a href="'.$button->getAction().'" target="'.$button->getTarget().'" class="'.$button->getStyle().'"> <i class="'.$button->getIcon().'"></i> '.$button->getName().'</a>';
            }
        }

        return $render;
    }

    public function grid($component)
    {
        switch ($component['type']) {
     case 'SELECT':
        return $this->renderButtonGridSelect($component['config'], $component['buttons']);
        break;
     case 'BUTTONS':
        return $this->renderButtonGrid($component['config'], $component['buttons']);
        break;
    case 'LINE':
        return $this->renderButtonGridLine($component['buttons']);
        break;
    }
    }

    private function renderButtonGridSelect($config, $buttons)
    {
        $seguranca = $this->app[Seguranca::class];

        $flag = 0;

        $render = '<div class="btn-group">
       <button type="button" class="btn '.$config['classButton'].'">'.$config['label'].'</button>
       <button type="button" class="btn '.$config['classButton'].' dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
          <span class="caret"></span>
          <span class="sr-only"></span>
       </button>';

        if (!empty($buttons)) {
            $render.= '<ul class="dropdown-menu" role="menu">';

            foreach ($buttons as $key => $button) {
                $rota = substr($button['action'], 1);

                if (!env('IS_SECURITY_ENNABLED') || $seguranca->haspermission($rota)) {
                    $flag += 1;
                    if ($button['method'] == 'get') {
                        $render.= '<li>
                                    <a href="'.$button['action'].'" class="'.$button['classButton'].'">
                                      <i class="'.$button['icon'].'"></i> '.$button['label'].'
                                    </a>
                                  </li>';
                    } else {
                        $render.= '<li>
                                        <form action="'.$button['action'].'" method="'.strtoupper($button['method']).'" class="form-singlebutton">
                                          <input type="hidden" name="id" value="'.$button['id'].'">
                                          <input type="hidden" name="_token" value="'.csrf_token().'">
                                          <input type="hidden" name="_method" value="'.strtoupper($button['method']).'">
                                          <button class="'.$button['classButton'].'"><i class="'.$button['icon'].'"></i> '.$button['label'].'</button>
                                        </form>
                                    </li>';
                    }
                }
            }
            $render.= '</ul></div>';

            if ($flag == 0) {
                //                $render = '<div class="btn-group">
//                    <button type="button" class="btn '.$config['classButton'].'">Sem Ações</button>
//                </div>';
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
            if (!env('IS_SECURITY_ENNABLED') || $seguranca->haspermission($button['action'])) {
                if ($config['showLabel']) {
                    $render .= '<a style="margin-right:5px"  href="'.$button['action'].'" class="btn '.$button['classButton'].'"> <i class="'.$button['icon'].'"></i> '.$button['label'].'</a>';
                } else {
                    $render .= '<a style="margin-right:5px" title="'.$button['label'].'" href="'.$button['action'].'" class="btn '.$button['classButton'].'"> <i class="'.$button['icon'].'"></i></a>';
                }
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
                $rota = substr($button['action'], 1);
                if (!env('IS_SECURITY_ENNABLED') || $seguranca->haspermission($rota)) {
                    $flag += 1;
                    if ($button['method'] == 'get') {
                        $render .= '<td style="padding-right: 5px">';
                        $render .= '<div class="btn-group">';
                        $render .= '<a href="'.$button['action'].'" class="'.$button['classButton'].'">';
                        $render .= '<i class="'.$button['icon'].'"></i> '.$button['label'];
                      //$render .= '</a>';
                      $render .= '</a></div>';
                        $render .= '</td>';
                    } else {
                        $render .= '<td style="padding-right: 5px">';
                        $render .= '<div class="btn-group">';
                        $render .= '<form action="'.$button['action'].'" method="'.strtoupper($button['method']).'" class="form-linebutton">';
                        $render .= '<input type="hidden" name="id" value="'.$button['id'].'">';
                        $render .= '<input type="hidden" name="_token" value="'.csrf_token().'">';
                        $render .= '<input type="hidden" name="_method" value="'.strtoupper($button['method']).'">';
                        $render .= '<button class="'.$button['classButton'].'"><i class="'.$button['icon'].'"></i> '.$button['label'].'</button>';
                        $render .= '</form></div>';
                        $render .= '</td>';
                      //$render .= '</form>';
                    }
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
