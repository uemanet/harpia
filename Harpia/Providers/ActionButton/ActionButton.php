<?php

namespace Harpia\Providers\ActionButton;

use Modulos\Seguranca\Providers\Security\Security;

class ActionButton{

   protected $app;

   public function __construct($app)
   {
      $this->app = $app;
   }

   public function render($buttons)
   {
      $render = '';

      $security = new Security($this->app);

      foreach ($buttons as $key => $button){
         if(!env('IS_SECURITY_ENNABLED') || $security->haspermission($button->getAction())){
            $render .= '<a href="'.$button->getAction().'" target="'.$button->getTarget().'" class="'.$button->getStyle().'"> <i class="'.$button->getIcon().'"></i> '.$button->getName().'</a>';
         }
      }

      return $render;
   }
}