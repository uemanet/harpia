<?php

namespace Harpia\Providers\ActionButton;

use Modulos\Seguranca\Providers\Seguranca\Seguranca;

class ActionButton{

   protected $app;

   public function __construct($app)
   {
      $this->app = $app;
   }

   public function render($buttons)
   {
      $render = '';

      $seguranca = $this->app[Seguranca::class];

      foreach ($buttons as $key => $button){
         if(!env('IS_SECURITY_ENNABLED') || $seguranca->haspermission($button->getAction())){
            $render .= '<a href="'.$button->getAction().'" target="'.$button->getTarget().'" class="'.$button->getStyle().'"> <i class="'.$button->getIcon().'"></i> '.$button->getName().'</a>';
         }
      }

      return $render;
   }

   public function grid($component)
   {
      switch ($component['type']) {
         case 'SELECT':
            return $this->renderButtonGridSelect($component['config'],$component['buttons']);
            break;
         case 'BUTTONS':
            return $this->renderButtonGrid($component['config'],$component['buttons']);
            break;
      }
   }

  private function renderButtonGridSelect($config,$buttons){
    $seguranca = $this->app[Seguranca::class];

    $render = '<div class="btn-group">
                   <button type="button" class="btn '.$config['classButton'].'">'.$config['label'].'</button>
                   <button type="button" class="btn '.$config['classButton'].' dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                      <span class="caret"></span>
                      <span class="sr-only"></span>
                   </button>';

    if(!empty($buttons)){
       $render.= '<ul class="dropdown-menu" role="menu">';

       foreach ($buttons as $key => $button){
          if(!env('IS_SECURITY_ENNABLED') || $seguranca->haspermission($button['action'])){
             // $render .= '<a   > ';

             if($button['method'] == 'get') {
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
    }

    return $render;
  }

   private function renderButtonGrid($config,$buttons){
      $render = '';

      foreach ($buttons as $key => $button){
         if(!env('IS_SECURITY_ENNABLED') || $seguranca->haspermission($button['action'])){
            if($config['showLabel']){
               $render .= '<a style="margin-right:5px"  href="'.$button['action'].'" class="btn '.$button['classButton'].'"> <i class="'.$button['icon'].'"></i> '.$button['label'].'</a>';
            }else{
               $render .= '<a style="margin-right:5px" title="'.$button['label'].'" href="'.$button['action'].'" class="btn '.$button['classButton'].'"> <i class="'.$button['icon'].'"></i></a>';
            }
         }
      }

      return $render;
   }
}