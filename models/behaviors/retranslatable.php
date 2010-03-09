<?php
/**
 * Retranslatable Model Behavior
 * 
 * Allows you to quickly initialize translatable records for models where data already exists
 *
 * @package app.models.behaviors
 * @author Jose Diaz-Gonzalez
 * @version 1.0
 * @copyright Jose Diaz-Gonzalez
 **/
class RetranslatableBehavior extends ModelBehavior {

/**
 * Initiate Retranslatable Behavior
 *
 * @param object $model
 * @param array $config
 * @return void
 * @author Jose Diaz-Gonzalez
 * @access public
 */
	function setup(&$model, $config = array()) {

	}


/**
 * Initialize Translation Tables
 * 
 * Does not check to see if records already exist for the specific ModelAlias/PrimaryKey/FieldName combination
 *
 * @param string $model 
 * @return void
 * @author Jose Diaz-Gonzalez
 * @access public
 */
	function initializeTranslationTable(&$model) {
		$attachedBehaviors = $behaviors = $model->Behaviors->attached();
		if (!in_array('Translate', $attachedBehaviors)) {
			trigger_error("{$model->alias} does not have the TranslateBehavior attached", E_USER_ERROR);
		}
		if (empty($model->Behaviors->Translate->settings[$model->alias])) {
			trigger_error("{$model->alias} does not have the TranslateBehavior correctly configured", E_USER_ERROR);
		}
		$fields = array();
		foreach ($model->Behaviors->Translate->settings[$model->alias] as $key => $potentialField) {
			if (is_array($potentialField)) {
				$fields[] = $key;
			} else if (is_numeric($key)) {
				$fields[] = $potentialField;
			}
		}

		$translateAppModelObject = $model->Behaviors->Translate->runtime[$model->alias]['model'];
		$translationModel = ClassRegistry::init($translateAppModelObject->name);

		$model->Behaviors->disable('Translate'); 
		$records = $model->find('all', array('recursive'=> -1, 'order'=>'id'));
		$t = $b = 0;
		foreach ($records as $key => $record) {
			foreach ($fields as $field) {
				$translationRecord = array(
					'locale' => 'pt_br',
					'model' => $model->alias,
					'foreign_key' => $record[$model->alias][$model->primaryKey],
					'field' => $field,
					'content' => $record[$model->alias][$field],
				);
				if ($translationModel->create($translationRecord) && $translationModel->save($translationRecord)) {
					$t++;
				}
			}
		}
	}

}
?>
