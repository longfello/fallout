<?php
$this->breadcrumbs=array(
    'Аватарки в чате'=>array('index'),
    'Управление',
);

$this->menu=array(
    array('label'=>'Добавить аватарку','url'=>array('create')),
);
?>

<?php $this->widget('booster.widgets.TbGridView',array(
    'id'=>'chat-avatar-base-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        'avatar_id',
        'image' =>  array(
            'name' => 'image',
            "type" => "raw",
            'value' => '"<img src=\"/images/chat/avatars/{$data->image}\" style=\"height:100px\">"',
        ),
        'owner' => [
            'name'  => 'owner',
            'type'  => 'raw',
            'value' => '$data->owner?CHtml::link("{$data->playerModel->user} [{$data->owner}]", array("/admin/players/update", "id" => $data->owner)):""'
        ],
        array(
            'class'=>'booster.widgets.TbButtonColumn',
            'template' => '{update}&nbsp;{delete}'
        ),
    ),
)); ?>
