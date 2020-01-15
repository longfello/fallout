<?php
  /**
   *
   */
?>

<?php
  if (Yii::app()->stat->model->email_confirmed == Players::YES){
	  echo(Window::highlight(t::get('Адрес электронной почты активирован')));
  } else {
	  echo(Window::error(t::get('Ошибка активации. Попробуйте выслать код активации повторно.')));
//	  echo(Window::error($this->widget('application.components.widgets.Activation', [], true)));
  }
?>
