<?php

class Users extends CWidget
{

  public $icon;

  public $items = array();

  public $htmlOptions = array();

  public $itemsCssClass;

  public $visible = TRUE;

  public function init()
  {

  }

  public function run()
  {

    if ($this->visible) {

      echo CHtml::openTag('li', $this->htmlOptions);

      $contentLink = CHtml::tag('i', array('class' => $this->icon), '', true);

      $contentLink .= CHtml::openTag('span');
      $contentLink .= ucfirst(Yii::app()->user->name);
      $contentLink .= CHtml::tag('i', array('class' => 'caret'), '', true);
      $contentLink .= CHtml::closeTag('span');

      echo CHtml::link($contentLink, '#', array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'));

      echo CHtml::openTag('ul', array('class' => $this->itemsCssClass));

      if (!empty($this->items)) {

        foreach ($this->items as $item) {
          echo CHtml::openTag('li', array());
          echo CHtml::link($item['label'], $item['url']);
          echo CHtml::closeTag('li');
        }

      }

      echo CHtml::closeTag('ul');

      echo CHtml::closeTag('li');

    }

  }

  public static function convertNames($data)
  {
    $ids = explode(',', $data['ids']);
    // $names = explode(',', $data['users']);

    $players = array();

    foreach ($ids as $i => $id) {
      $name = $id; //$names[$i] ? $names[$i] : '???';
      $players[] = "<a href='/admin/players/update?id={$id}' title='id {$id}'>{$name}</a>";
    }

    return implode(', ', $players);
  }

}