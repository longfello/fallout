<?php
/* @var $this DefaultController */
?>
<?
$this->pageTitle = t::get('Ачивки за игровые достижения');
?>

<span style="float:right"><a href="library.php" style="text-decoration:underline;" onMouseOver="this.style='text-decoration:none'" onMouseOut="this.style='text-decoration:underline'"><img src="img/back.png" /> <?= t::get('Вернуться к разделам библиотеки') ?></a></span>
<p><h2><?= t::get('Достижения') ?></h2></p>
<br />
<table cellspacing="0" cellpadding="0" width="100%">
    <tr valign="bottom">
        <td class="tab" align="center" valign="middle" width="10%"><h3></h3></td>
        <td class="tab" align="center" valign="middle" width="10%"><h3><?= t::get('Наименование') ?></h3></td>
        <td class="tab" align="center" valign="middle" width="50%"><h3><?= t::get('Описание') ?></h3></td>
    </tr>
    <? foreach ($achieves as $achieve): ?>
    <tr valign="bottom">
        <?php
          /** @var $achieve Achieve */
          $file     = '/images/achieve/full/' . $achieve->pic;
          if (!file_exists(basedir.$file)) {
            $file     = '/images/achieve/'.$achieve->pic;
            if (!file_exists(basedir.$file)) {
              $file   = '/images/achieve/default.png';
            }
          }

        $hint = "<table border=0 cellspacing=0 cellpadding=0 width=250>
            <tr><td align='center'><b><img src='{$file}' style='max-width:200px;'></td></tr>
            <tr><td align='center'><b>" . $achieve->t('name') . "</b></td></tr>
            <tr><td align='center'>" . $achieve->t('desc') . "</td></tr>
            </table>";
        $hint = "<span id=\"achievehintfor$achieve->id\" style=\"display:none;\">$hint</span>";
        ?>
        <td align="center" class="tab" valign="middle">
          <?= $hint ?>
          <?= CHtml::image('/images/achieve/' . $achieve->pic, $achieve->name, array('onmouseover' => "hint(document.getElementById('achievehintfor$achieve->id').innerHTML, event);", 'onmouseout' => "c();")) ?>
        </td>
        <td align="center" class="tab" valign="middle"><?= $achieve->t('name') ?></td>
        <td align="center" class="tab" valign="middle"><?= $achieve->t('desc') ?></td>
    </tr>
    <? endforeach ?>
</table>