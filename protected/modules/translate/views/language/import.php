<?php
/* @var $this LanguageController */
/* @var $model Language */

$this->breadcrumbs=array(
    'Языки'=>array('index'),
    $lang->name=>array('view','id'=>$lang->id),
    'Импорт',
);

$this->menu=array(
    array('label'=>'Список', 'url'=>array('index')),
    array('label'=>'Добавить', 'url'=>array('create')),
    array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$lang->id)),
    array('label'=>'Управление', 'url'=>array('admin')),
);
?>

<h1>Импорт локализации языка #<?php echo $lang->id; ?></h1>

<?php
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'import-form',
    'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
));
?>

    <div class="row">

        <?php
        $this->widget('CMultiFileUpload', array(
            'name' => 'xml',
            'accept' => 'xml',
            'max' => '1',
            'denied' => 'Неправильный тип файла',
        ));

        ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Импортировать'); ?>
    </div>

<?php $this->endWidget($form); ?>