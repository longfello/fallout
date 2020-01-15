<?php

class ApiController extends Controller
{
  public $pageTitle = 'API';
  /**
   * @return array action filters
   */
  public function filters()
  {
    return array(
        'accessControl', // perform access control for CRUD operations
        'postOnly + field', // we only allow deletion via POST request
    );
  }

  /**
   * Specifies the access control rules.
   * This method is used by the 'accessControl' filter.
   * @return array access control rules
   */
  public function accessRules()
  {
    return array(
        array('allow', // allow admin user to perform 'admin' and 'delete' actions
            'actions' => array('field', 'autocomplete'),
            'roles' => array('admin'),
        ),
        array('deny',  // deny all users
            'users' => array('*'),
        ),
    );
  }

	public function actionField(){
	    $data = Yii::app()->request->getParam('data', array());
	    if ($data){
	      $model = CActiveRecord::model($data['model'])->findByPk($data['id']);
	      $model->setAttribute($data['field'], $data['value']);
	      if ($model->save()){
	        echo(json_encode(array('result' => 1)));
	      } else {
	        echo(json_encode(array('result' => 0, 'error' => CHtml::errorSummary($model))));
	      }
	    }
	    Yii::app()->end();
	}

	public function actionAutocomplete(){
		$category = Yii::app()->request->getParam('category', '');
		$term     = Yii::app()->request->getParam('term', '');
		$type     = Yii::app()->request->getParam('type', '');
		$lang     = Yii::app()->request->getParam('lang', false);
		$langClause = $lang?"AND lang_id=".(int)$lang:"";

		$res = [];
		switch($category){
			case 'equipment':
				$limit = 10;
				$ctype  = '1=1';
				switch ($type){
					case 'weapon':
						$ctype = "type='W'";
						break;
					case 'armor':
						$ctype = "type='A'";
						break;
					case 'equipment':
						$ctype = "type='T' or type='U'";
						break;
					case 'food':
						$ctype = "type='F'";
						break;
					case 'energy':
						$ctype = "type='D'";
						break;
					default:
						$limit = 0;
				}

				$query = "
					SELECT foo.id, tr.value, foo.type FROM(
					  SELECT *, CONCAT('@@@equipment@@name@@id@@', t.id) slug FROM `equipment` `t` 
					  WHERE clan=0 AND ($ctype)
					) as foo
					LEFT JOIN (
					  SELECT * FROM rev_language_translate WHERE slug LIKE '@@@equipment@@name@@id@@%' AND lang_id IN (1, 2) {$langClause}
					) tr ON tr.slug = foo.slug
					WHERE (tr.value LIKE '%$term%' OR foo.id = '$term') 
					ORDER BY tr.value
					LIMIT $limit";
				$models = Yii::app()->db->createCommand($query)->queryAll();
				foreach ($models as $one){
					$label = "{$one['type']}) {$one['value']} [{$one['id']}]";
					$res[$label] = [
						'id'    => $one['id'],
						'value' => $one['id'],
						'label' => $label
					];
				}
				sort($res);
				break;
			case 'tatoo':
				$limit = 10;
				$player_id     = Yii::app()->request->getParam('player_id', '0');

				$where_add = '';
				if ($player_id>0) {
					$player_model = Players::model()->findByPk($player_id);
					if ($player_model) {
						$where_add = "OR clan='{$player_model->clan}' OR owner='{$player_model->id}'";
					}
				}

				$query = "
					SELECT foo.id, tr.value FROM(
					  SELECT *, CONCAT('@@@tatoos@@name@@id@@', t.id) slug FROM `tatoos` `t` 
					  WHERE (clan=0 AND owner=0) $where_add
					) as foo
					LEFT JOIN (
					  SELECT * FROM rev_language_translate WHERE slug LIKE '@@@tatoos@@name@@id@@%' AND lang_id IN (1, 2) {$langClause}
					) tr ON tr.slug = foo.slug
					WHERE (tr.value LIKE '%$term%' OR foo.id = '$term') 
					ORDER BY tr.value
					LIMIT $limit";
				
				$models = Yii::app()->db->createCommand($query)->queryAll();
				foreach ($models as $one){
					$label = "{$one['value']} [{$one['id']}]";
					$res[$label] = [
						'id'    => $one['id'],
						'value' => $one['id'],
						'label' => $label
					];
				}
				sort($res);
				break;
			case 'player':
				$limit = 10;

				$query = "
					SELECT p.id, p.user, p.email FROM players p
					WHERE (p.user LIKE '%$term%' OR p.id = '$term' OR p.email LIKE '%$term%') 
					ORDER BY p.user
					LIMIT $limit";
				$models = Yii::app()->db->createCommand($query)->queryAll();
				foreach ($models as $one){
					$label = "{$one['user']} [{$one['id']}] [{$one['email']}]";
					$res[$label] = [
						'id'    => $one['id'],
						'value' => $one['id'],
						'label' => $label
					];
				}
				sort($res);
				break;
			case 'clanitem':
				$limit = 10;
				$clan_id = Yii::app()->request->getParam('clan_id', '0');

				$query = "
					SELECT foo.id, tr.value, foo.type FROM(
					  SELECT *, CONCAT('@@@equipment@@name@@id@@', t.id) slug FROM `equipment` `t`
					) as foo
					LEFT JOIN (
					  SELECT * FROM rev_language_translate WHERE slug LIKE '@@@equipment@@name@@id@@%' AND lang_id IN (1, 2)
					) tr ON tr.slug = foo.slug
					INNER JOIN cstore cs ON cs.item=foo.id AND cs.clan='".$clan_id."'
					WHERE (tr.value LIKE '%$term%' OR foo.id = '$term') 
					ORDER BY tr.value
					LIMIT $limit";
				
				$models = Yii::app()->db->createCommand($query)->queryAll();
				foreach ($models as $one){
					$label = "{$one['type']}) {$one['value']} [{$one['id']}]";

					$count_sql = "SELECT
									COUNT(cs.id)
								  FROM
									cstore cs
								  WHERE cs.`item`='{$one['id']}' AND cs.`clan`='{$clan_id}'";
					$items_count = intval(Yii::app()->db->createCommand($count_sql)->queryScalar());
						
					$res[$label] = [
						'id'    => $one['id'],
						'value' => $items_count,
						'label' => $label
					];
				}
				sort($res);
				break;
            case 'playeritem':
                $limit = 10;
                $player_id = Yii::app()->request->getParam('player_id', '0');
                $place = Yii::app()->request->getParam('place', 'inv');

                $join_sql = "";
                $count_sql = "";
                switch($place){
                    case 'inv':
                        $join_sql = "INNER JOIN uequipment ue ON ue.item=foo.id AND ue.owner='".$player_id."'";
                        $count_sql = "SELECT
									COUNT(ue.id)
								  FROM
									uequipment ue
								  WHERE ue.`item`='%s' AND ue.owner='".$player_id."'";
                        break;
                    case 'store':
                        $join_sql = "INNER JOIN bstore bs ON bs.item=foo.id AND bs.player='".$player_id."'";
                        $count_sql = "SELECT
									COUNT(bs.id)
								  FROM
									bstore bs
								  WHERE bs.`item`='%s' AND bs.player='".$player_id."'";
                        break;
                }

                if ($join_sql!='' && $count_sql!='') {
                    $query = "
					SELECT foo.id, tr.value, foo.type FROM(
					  SELECT *, CONCAT('@@@equipment@@name@@id@@', t.id) slug FROM `equipment` `t`
					) as foo
					LEFT JOIN (
					  SELECT * FROM rev_language_translate WHERE slug LIKE '@@@equipment@@name@@id@@%' AND lang_id IN (1, 2)
					) tr ON tr.slug = foo.slug
					{$join_sql}
					WHERE (tr.value LIKE '%$term%' OR foo.id = '$term') 
					ORDER BY tr.value
					LIMIT $limit";

                    $models = Yii::app()->db->createCommand($query)->queryAll();
                    foreach ($models as $one){
                        $label = "{$one['type']}) {$one['value']} [{$one['id']}]";

                        $items_count = intval(Yii::app()->db->createCommand(sprintf($count_sql,$one['id']))->queryScalar());

                        $res[$label] = [
                            'id'    => $one['id'],
                            'value' => $items_count,
                            'label' => $label
                        ];
                    }
                    sort($res);
                }
                break;
			case 'NpcType':
				$limit = 10;

				$query = "
					SELECT foo.id, tr.value FROM(
					  SELECT *, CONCAT('@@@npc_type@@name@@id@@', t.id) slug FROM `npc_type` `t` 
					) as foo
					LEFT JOIN (
					  SELECT * FROM rev_language_translate WHERE slug LIKE '@@@npc_type@@name@@id@@%' AND lang_id IN (1, 2)
					) tr ON tr.slug = foo.slug
					WHERE (tr.value LIKE '%$term%' OR foo.id = '$term') 
					ORDER BY tr.value
					LIMIT $limit";
				$models = Yii::app()->db->createCommand($query)->queryAll();
				foreach ($models as $one){
					$label = "{$one['value']} [{$one['id']}]";
					$res[$label] = [
						'id'    => $one['id'],
						'value' => $one['value'],
						'label' => $label
					];
				}
				sort($res);
				break;
			case 'Npc':
				$limit = 10;
				$query = "
					SELECT foo.id, tr.value FROM(
					  SELECT *, CONCAT('@@@npc@@name@@id@@', t.id) slug FROM `npc` `t` 
					) as foo
					LEFT JOIN (
					  SELECT * FROM rev_language_translate WHERE slug LIKE '@@@npc@@name@@id@@%' AND lang_id IN (1, 2) {$langClause}
					) tr ON tr.slug = foo.slug
					WHERE (tr.value LIKE '%$term%' OR foo.id = '$term') 
					ORDER BY tr.value
					LIMIT $limit";
				$models = Yii::app()->db->createCommand($query)->queryAll();
				foreach ($models as $one){
					$label = "{$one['value']} [{$one['id']}]";
					$res[$label] = [
						'id'    => $one['id'],
						'value' => $one['value'],
						'label' => $label
					];
				}
				sort($res);
				break;
			case 'Recipes':
				$limit = 10;

				$query = "
					SELECT foo.recipe_id id, tr.value FROM(
					  SELECT *, CONCAT('@@@recipes@@recipe_name@@recipe_id@@', t.recipe_id) slug FROM `recipes` `t` 
					) as foo
					LEFT JOIN (
					  SELECT * FROM rev_language_translate WHERE slug LIKE '@@@recipes@@recipe_name@@recipe_id@@%' AND lang_id IN (1, 2) {$langClause}
					) tr ON tr.slug = foo.slug
					WHERE (tr.value LIKE '%$term%' OR foo.recipe_id = '$term') 
					ORDER BY tr.value
					LIMIT $limit";
				$models = Yii::app()->db->createCommand($query)->queryAll();
				foreach ($models as $one){
					$label = "{$one['value']} [{$one['id']}]";
					$res[$label] = [
						'id'    => $one['id'],
						'value' => $one['value'],
						'label' => $label
					];
				}
				sort($res);
				break;
			case 'Equipment':
				$limit = 10;

				$query = "
					SELECT foo.id, tr.value, foo.type FROM(
					  SELECT *, CONCAT('@@@equipment@@name@@id@@', t.id) slug FROM `equipment` `t` 
					  WHERE clan=0
					) as foo
					LEFT JOIN (
					  SELECT * FROM rev_language_translate WHERE slug LIKE '@@@equipment@@name@@id@@%' AND lang_id IN (1, 2) {$langClause}
					) tr ON tr.slug = foo.slug
					WHERE (tr.value LIKE '%$term%' OR foo.id = '$term') 
					ORDER BY tr.value
					LIMIT $limit";
				$models = Yii::app()->db->createCommand($query)->queryAll();
				foreach ($models as $one){
					$label = "{$one['value']} [{$one['id']}]";
					$res[$label] = [
						'id'    => $one['id'],
						'value' => $one['value'],
						'label' => $label
					];
				}
				sort($res);
				break;
            case 'Crafting':
                $limit = 10;

                $query = "
					SELECT foo.id, tr.value FROM(
					  SELECT *, CONCAT('@@@crafting@@name@@id@@', c.id) slug FROM `crafting` `c` 
					) as foo
					LEFT JOIN (
					  SELECT * FROM rev_language_translate WHERE slug LIKE '@@@crafting@@name@@id@@%' AND lang_id IN (1, 2) {$langClause}
					) tr ON tr.slug = foo.slug
					WHERE (tr.value LIKE '%$term%' OR foo.id = '$term') 
					ORDER BY tr.value
					LIMIT $limit";
                $models = Yii::app()->db->createCommand($query)->queryAll();
                foreach ($models as $one){
                    $label = "{$one['value']} [{$one['id']}]";
                    $res[$label] = [
                        'id'    => $one['id'],
                        'value' => $one['value'],
                        'label' => $label
                    ];
                }
                sort($res);
                break;
		}
		header('Content-type: application/json');
		echo CJSON::encode($res);
		foreach (Yii::app()->log->routes as $route) {
			if($route instanceof CWebLogRoute) {
				$route->enabled = false; // disable any weblogroutes
			}
		}
		Yii::app()->end();

	}
}