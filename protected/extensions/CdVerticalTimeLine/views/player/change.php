<?php
if (!function_exists('paintValue')) {
  function paintValue($value){
    if (strlen($value) > 100) return (mb_substr($value, 0, 100) . '...');
    if (empty($value)) return "[ пусто ]";
    return $value;
  }
}
?>
<p>Игроку #<a href="/admin/players/update?id=<?=isset($data->id)?$data->id:-1?>"><?=isset($data->name)?$data->name:"???"?></a> изменены параметры:</p>
<ol>
  <?php foreach($data->log as $param => $value){ ?>
    <li>
      <b><?=Players::model()->getAttributeLabel($param);?>:</b>
      <?= paintValue($value->old) ?> &longrightarrow; <?= paintValue($value->new) ?>
    </li>
  <?php } ?>
</ol>

<?php
  $admin = User::model()->findByPk($data->admin);
  /** @var $admin User */
  $name  = $admin?$admin->username:'?';
$name .= " [{$data->admin}]";
?>
<p>Изменения внес администратор <?= $name ?></p>
