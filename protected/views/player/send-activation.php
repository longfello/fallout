<?php

  /**
   *
   */

?>
<?php if ($error) {
	?>
	<?= Window::error($error) ?>
	<?php
} else {
  ?>
	<?= Window::highlight(t::get('Код активации выслан на адрес электронной почты %s', [Yii::app()->stat->model->email]))?>

  <?php
}
?>

