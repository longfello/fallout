<p><?= $data->message ?></p>
<?php
  $admin = User::model()->findByPk($data->admin);
  /** @var $admin User */
  $name  = $admin?$admin->username:'?';
$name .= " [{$data->admin}]";
?>
<p>Изъял администратор <?= $name ?></p>
