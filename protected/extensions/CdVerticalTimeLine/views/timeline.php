<?php
/**
 * Timeline layout itself
 */
/* @var $this CdVerticalTimeLine */
?>

<div class="row">
  <div class="col-md-12">

<?php

echo CHtml::openTag('div', $this->containerOptions);
echo CHtml::openTag('ul', $this->listOptions);

foreach ( $this->events as $event )
{
    echo $this->getEventContent($event);
}

?>

  <li>
    <i class="fa fa-clock-o bg-gray"></i>
  </li>

<?php

echo CHtml::closeTag('ul');
echo CHtml::closeTag('div');
echo CHtml::closeTag('div');
echo CHtml::closeTag('div');