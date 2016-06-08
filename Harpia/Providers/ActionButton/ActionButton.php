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

      $seguranca = new Seguranca($this->app);

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
      $render = '<div class="btn-group">
                     <button type="button" class="btn '.$config['classButton'].'">'.$config['label'].'</button>
                     <button type="button" class="btn '.$config['classButton'].' dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only"></span>
                     </button>';

      if(count($buttons)){
         $render.= '<ul class="dropdown-menu" role="menu">';         
      }

      foreach ($buttons as $key => $button){
         if(!env('IS_SECURITY_ENNABLED') || $seguranca->haspermission($button['action'])){
            // $render .= '<a   > ';
            $render.= '<li><a href="'.$button['action'].'" class="'.$button['classButton'].'" target="'.$button['target'].'"><i class="'.$button['icon'].'"></i> '.$button['label'].'</a></li>';
         }
      }

      $render.= '</ul></div>';

      return $render;
   }

   private function renderButtonGrid($config,$buttons){
      $render = '';

      foreach ($buttons as $key => $button){
         if(!env('IS_SECURITY_ENNABLED') || $seguranca->haspermission($button['action'])){
            if($config['showLabel']){
               $render .= '<a style="margin-right:5px"  href="'.$button['action'].'" target="'.$button['target'].'" class="btn '.$button['classButton'].'"> <i class="'.$button['icon'].'"></i> '.$button['label'].'</a>';
            }else{
               $render .= '<a style="margin-right:5px" title="'.$button['label'].'" href="'.$button['action'].'" target="'.$button['target'].'" class="btn '.$button['classButton'].'"> <i class="'.$button['icon'].'"></i></a>';
            }
         }
      }

      return $render;
   }
}