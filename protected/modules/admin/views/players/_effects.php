<?php
/**
 *
 * @var Players $model
 */

$effects = EqTempEffect::model()->findAllByAttributes(array('player_id'=>$model->id,'type'=>0),array('order'=>'end_time DESC'));
$post_effects = EqTempEffect::model()->findAllByAttributes(array('player_id'=>$model->id,'type'=>1),array('order'=>'end_time DESC'));
?>
<h3 class="box-title">Эффекты действующие на игрока</h3>
<div class="box box-primary">
    <div class="box-header">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#user_effects" aria-controls="user_effects" role="tab" data-toggle="tab">Эффекты</a></li>
            <li role="presentation"><a href="#user_posteffects" aria-controls="user_posteffects" role="tab" data-toggle="tab">Постэффекты</a></li>
        </ul>
    </div><!-- /.box-header -->
    <div class="box-body">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="user_effects">
                <ul class="todo-list ui-sortable">
                    <?php foreach($effects as $effect) { ?>
                        <li>
                            <span class="label <?= ($effect->end_time>time())?'label-success':'label-warning'; ?>" title="Конец действия эффекта"><i class="fa fa-clock-o"></i> <?= Tool::dateDiff(new DateTime("@{$effect->end_time}")); ?></span>
                            <a href="<?= $this->createUrl('equipment/update', array('id' => $effect->eq_id)) ?>" target="_blank" class="btn btn-xs btn-primary"><?= $effect->equipment->name; ?> [<?= $effect->eq_id; ?>]</a>
                            <!-- General tools such as edit or delete-->
                            <dl class="dl-horizontal m-t-md">
                            <?php
                                $eq_effects = array(
                                    'add_strength' => 'Сила',
                                    'add_agility' => 'Ловкость',
                                    'add_defense' => 'Защита',
                                    'add_max_energy' => 'Макс. энергия',
                                    'add_max_hp' => 'Макс. здоровье',
                                    'add_energy' => 'Энергия',
                                    'add_hp' => 'Здоровье',
                                    'add_pohod' => 'Походные'
                                );
                                foreach ($eq_effects as $eq_effect_slug=>$eq_effect_name) {
                                    $cur_effect = intval($effect->equipment->getAttribute($eq_effect_slug));
                                    if ($cur_effect!=0) {
                                        echo "<dt><span class='text'>$eq_effect_name</span></dt><dd>$cur_effect</dd>";
                                    }
                                }
                            ?>
                            </dl>
                        </li>
                    <?php } ?>
                </ul>
                <?php  if (count($effects) == 0) { ?>
                    <div class="alert alert-info">
                        <b>Игрок не имеет эффектов.</b>
                    </div>
                <?php  } ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="user_posteffects">
                <ul class="todo-list ui-sortable">
                    <?php foreach($post_effects as $effect) { ?>
                    <li>
                        <span class="label <?= ($effect->end_time>time())?'label-success':'label-warning'; ?>" title="Конец действия постэффекта"><i class="fa fa-clock-o"></i> <?= Tool::dateDiff(new DateTime("@{$effect->end_time}")); ?></span>
                        <a href="<?= $this->createUrl('equipment/update', array('id' => $effect->eq_id)) ?>" target="_blank" class="btn btn-xs btn-primary"><?= $effect->equipment->name; ?> [<?= $effect->eq_id; ?>]</a>
                        <!-- General tools such as edit or delete-->
                        <dl class="dl-horizontal m-t-md">
                            <?php
                            $eq_post_effects = array(
                            'post_strength' => 'Сила',
                            'post_agility' => 'Ловкость',
                            'post_defense' => 'Защита',
                            'post_max_energy' => 'Макс. энергия',
                            'post_max_hp' => 'Макс. здоровье',
                            'post_energy' => 'Энергия',
                            'post_hp' => 'Здоровье',
                            );

                            foreach ($eq_post_effects as $eq_effect_slug=>$eq_effect_name) {
                                $cur_effect = intval($effect->equipment->getAttribute($eq_effect_slug));
                                if ($cur_effect!=0) {
                                    echo "<dt><span class='text'>$eq_effect_name</span></dt><dd>$cur_effect</dd>";
                                }
                            }
                            ?>
                        </dl>
                    </li>
                    <?php } ?>
                </ul>
                <?php  if (count($post_effects) == 0) { ?>
                    <div class="alert alert-info">
                        <b>Игрок не имеет постэффектов.</b>
                    </div>
                <?php  } ?>
            </div>
        </div>
    </div><!-- /.box-body -->
</div>