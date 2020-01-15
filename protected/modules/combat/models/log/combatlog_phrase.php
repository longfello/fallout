<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 15.06.16
 * Time: 15:55
 */
class combatlog_phrase {
  const EVENT_NO_ENERGY = 'no_energy';
  const EVENT_KICK      = 'kick';
  const EVENT_BLOCK     = 'block';
  const EVENT_WIN       = 'win';
  const EVENT_LOOSE     = 'loose';

  public $event;
  public $data = array();
  /** @var combatlog_prototype | null */
  public $log;

  public function __construct($event, $data = array()){
    $this->data  = $data;
    $this->event = $event;

    $classname = 'combatlog_'.$this->event;
    if (class_exists($classname)) {
      $this->log = new $classname();
    } else {
      trigger_error('No combat log class available: ' . $classname);
    }
  }

  public function render(){
    return $this->log->render($this->data);
  }

  public function getData(){
    return array(
      'event' => $this->event,
      'data'  => $this->data
    );
  }

  public static function load($data) {
    if (is_array($data) && isset($data['event'])  && isset($data['data'])) {
      return new combatlog_phrase($data['event'], $data['data']);
    } else trigger_error('Phrase data or event absent!');
    return false;
  }

  public function save($combat_id, $round_id){
    $this->log->save($combat_id, $round_id, $this->data);
  }
}