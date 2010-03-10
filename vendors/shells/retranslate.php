<?php
/**
 * Retranslate Shell
 *
 * Initialize Translation Tables for a model
 *
 * @category Shell
 * @package  GreenMap
 * @version  1.0
 * @author   Jose Diaz-Gonzalez
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.josediazgonzalez.com
 */
class RetranslateShell extends Shell {
	function main() {
		if (empty($this->args)) {
			return $this->out('You need to specify a modelname');
		}

		$modelName = $this->args[0];
		
		$lang = isset($this->args[1]) ? $this->args[1] : 'eng';
		
		$model = ClassRegistry::init($modelName);
		$model->initializeTranslationTable($lang);
	}
}
?>