<?php

namespace Modulos\Seguranca\Providers\MasterMenu;


class HtmlContent
{
    protected $tags;

    public function __construct()
    {
        $this->tags = array();
    }


    public function addTag($tag, $href = null, $class = null, $style = null)
    {
        $this->tags[] = array('tag' => $tag, 'href' => $href, 'class' => $class, 'style' => $style);
    }

    public function addContent($content)
    {
        // Conteudo deve ser String
        if(!is_string($content))
            throw new \Exception();

        array_push($this->tags[count($this->tags) - 1]['content'], $content);
    }

    public function render()
    {
      $render = '';
      foreach ($this->tags as $tag){
          // Abertura de tag
          $render .= '<'.$tag['tag'];

          if(!is_null($tag['class']))
              $render .= ' class="'.$tag['class'].'"';

          if(!is_null($tag['href']))
              $render .= ' href="'.$tag['href'].'"';

          if(!is_null($tag['style']))
              $render .= ' style="'.$tag['style'].'">';

          if(!is_null($tag['content']))
              $render .= $tag['content'];

          $render .= '</'.$tag['tag'].'>';
      }
    }
}