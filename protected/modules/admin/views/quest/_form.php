<?php /**
 * @var $form MLActiveForm
 * @var $model Quest
 */

$form=$this->beginWidget('MLActiveForm',array(
	'id'=>'quest-form',
	'enableAjaxValidation'=>false,
)); ?>

  <?php echo $form->errorSummary($model); ?>

<div>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#p1" aria-controls="p1" role="tab"
                                                  data-toggle="tab">Основное</a></li>
        <li role="presentation"><a href="#p2" aria-controls="p2" role="tab" data-toggle="tab">Задание</a></li>
        <li role="presentation"><a href="#p3" aria-controls="p3" role="tab" data-toggle="tab">Необходимые
                предметы/рецепты</a></li>
        <li role="presentation"><a href="#p4" aria-controls="p4" role="tab" data-toggle="tab">Приз</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="p1">
            <div class="row well">
                <div class="col-xs-12">
                    <?php echo $form->numberFieldGroup($model, 'minlev', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
                    <?php echo $form->numberFieldGroup($model, 'maxlev', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
                    <?php echo $form->MLtextAreaVisualGroup($model, 'opis', 'opis'); ?>
                    <?php echo $form->MLtextAreaVisualGroup($model, 'q_check', 'q_check'); ?>
                    <?php echo $form->MLtextAreaVisualGroup($model, 'q_done', 'q_done'); ?>
                    <?php echo $form->dropDownListGroup($model, 'questor', array('widgetOptions' => array('data' => array("D" => "Дейзи", "H" => "Логово Охотников",), 'htmlOptions' => array('class' => 'input-large')))); ?>
                    <?php echo $form->dropDownListGroup($model, 'enabled', array('widgetOptions' => array('data' => array("0" => "Запрещен", "1" => "Разрешен",), 'htmlOptions' => array('class' => 'input-large')))); ?>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="p2">
            <div class="row well">
                <div class="col-xs-12">
                    <?php echo $form->autocompleteInput($model, 'npcs', 'NpcType', 'id', 'name', [], 10); ?>
                    <?php echo $form->numberFieldGroup($model, 'amount', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
                    <?php echo $form->autocompleteInput($model, 'drop1', 'Equipment', 'id', 'name', [], 1); ?>
                    <?php /* echo $form->textFieldGroup($model,'drop1',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); */ ?>
                    <?php echo $form->numberFieldGroup($model, 'drop1_amount', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
                    <?php echo $form->autocompleteInput($model, 'drop2', 'Equipment', 'id', 'name', [], 1); ?>
                    <?php /* echo $form->textFieldGroup($model,'drop2',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); */ ?>
                    <?php echo $form->numberFieldGroup($model, 'drop2_amount', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
                    <?php echo $form->numberFieldGroup($model, 'pvp', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="p3">
            <div class="row well">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Предмет #1</h3>
                        </div>
                        <div class="panel-body">
                            <?php echo $form->autocompleteInput($model, 'qitem1', 'Equipment', 'id', 'name', [], 1); ?>
                            <?php echo $form->numberFieldGroup($model, 'qitem1count', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Рецепт #1</h3>
                        </div>
                        <div class="panel-body">
                            <?php echo $form->autocompleteInput($model, 'qrecipe1', 'Recipes', 'recipe_id', 'recipe_name', [], 1); ?>
                            <?php echo $form->numberFieldGroup($model, 'qrecipe1count', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Предмет #2</h3>
                        </div>
                        <div class="panel-body">
                            <?php echo $form->autocompleteInput($model, 'qitem2', 'Equipment', 'id', 'name', [], 1); ?>
                            <?php echo $form->numberFieldGroup($model, 'qitem2count', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Рецепт #2</h3>
                        </div>
                        <div class="panel-body">
                            <?php echo $form->autocompleteInput($model, 'qrecipe2', 'Recipes', 'recipe_id', 'recipe_name', [], 1); ?>
                            <?php echo $form->numberFieldGroup($model, 'qrecipe2count', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Предмет #3</h3>
                        </div>
                        <div class="panel-body">
                            <?php echo $form->autocompleteInput($model, 'qitem3', 'Equipment', 'id', 'name', [], 1); ?>
                            <?php echo $form->numberFieldGroup($model, 'qitem3count', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Рецепт #3</h3>
                        </div>
                        <div class="panel-body">
                            <?php echo $form->autocompleteInput($model, 'qrecipe3', 'Recipes', 'recipe_id', 'recipe_name', [], 1); ?>
                            <?php echo $form->numberFieldGroup($model, 'qrecipe3count', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Предмет #4</h3>
                        </div>
                        <div class="panel-body">
                            <?php echo $form->autocompleteInput($model, 'qitem4', 'Equipment', 'id', 'name', [], 1); ?>
                            <?php echo $form->numberFieldGroup($model, 'qitem4count', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Рецепт #4</h3>
                        </div>
                        <div class="panel-body">
                            <?php echo $form->autocompleteInput($model, 'qrecipe4', 'Recipes', 'recipe_id', 'recipe_name', [], 1); ?>
                            <?php echo $form->numberFieldGroup($model, 'qrecipe4count', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="p4">
            <div class="row well">
                <div class="col-xs-12">
                    <?php echo $form->autocompleteInput($model, 'item', 'Equipment', 'id', 'name', [], 1); ?>
                    <?php echo $form->numberFieldGroup($model, 'itemcount', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
                    <?php echo $form->numberFieldGroup($model, 'plat', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
                    <?php echo $form->numberFieldGroup($model, 'gold', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
                    <?php echo $form->numberFieldGroup($model, 'experience', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($model->isNewRecord) { ?>
    <div class="form-group">
        <label class="col-sm-4 control-label">Количество создаваемых квестов:</label>
        <div class="col-sm-8">
            <?= CHtml::numberField('col_quests', 1, array('class' => 'form-control', 'min'=>1)); ?>
        </div>
    </div>
<?php } ?>


<div class="form-actions">
	<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType'=>'submit',
			'context'=>'primary',
			'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
		)); ?>
</div>

<?php $this->endWidget(); ?>
