<?php
$this->breadcrumbs=array(
    'Рецепты'=>array('index'),
    'Управление',
);

$this->menu=array(
    array('label'=>'Создать рецепт','url'=>array('create')),
);
?>

<?php $this->widget('booster.widgets.TbGridView',array(
    'id'=>'recipes-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        'recipe_id',
        'recipe_type_id' => array(
            'name'        => 'recipe_type_id',
            'filter'      => CHtml::dropDownList( 'Recipes[recipe_type_id]', $model->recipe_type_id,
                [null => '-'] + CHtml::listData( RecipeTypes::model()->findAll( array( 'order' => 'recipe_type_name' ) ), 'recipe_type_id', 'recipe_type_name' )
            ),
            'value' => '$data->recipe_type_id?"{$data->recipeTypeModel->recipe_type_name}":""'
        ),
        'recipe_name',
        /*'recipe_description',*/
        'crafting_id' => array(
            'name'  => 'crafting_id',
            'type'  => 'raw',
            'value' => '$data->crafting_id?CHtml::link("{$data->craftingModel->name} [{$data->crafting_id}]", array("/admin/crafting/update", "id" => $data->crafting_id)):""'
        ),
        'cost',
        'using_cnt',
        'location' => [
            'name' => 'location',
            'value' => 'isset($data->availableLocations[$data->location])?$data->availableLocations[$data->location]:"?"',
            'filter' => CHtml::dropDownList('Recipes[location]', $model->location, [null => '-'] + $model->availableLocations)
        ],
        array(
            'class'=>'booster.widgets.TbButtonColumn',
            'template' => '{update}&nbsp;{delete}',
        ),
    ),

)); ?>
