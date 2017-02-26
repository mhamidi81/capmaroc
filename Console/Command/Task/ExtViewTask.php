<?php
/**
 * The ViewTask handles creating and updating views files.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 1.2
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('ViewTask', 'Console/Command/Task'); 

/**
 * Task class for creating and updating model files.
 *
 * @package       Cake.Console.Command.Task
 */
class ExtViewTask extends ViewTask {

	public $name = 'View';
	
	public $noTemplateActions = array('admin_delete', 'admin_view', 'admin_add', 'admin_edit', 'admin_get_datagrid_data');
	
	public function execute() {
		
		parent::execute();
	}
/**
 * Loads Controller and sets variables for the template
 * Available template variables
 *	'modelClass', 'primaryKey', 'displayField', 'singularVar', 'pluralVar',
 *	'singularHumanName', 'pluralHumanName', 'fields', 'foreignKeys',
 *	'belongsTo', 'hasOne', 'hasMany', 'hasAndBelongsToMany'
 *
 * @return array Returns a variables to be made available to a view template
 */
	protected function _loadController() {
		if (!$this->controllerName) {
			$this->err(__d('cake_console', 'Controller not found'));
		}

		$plugin = null;
		if ($this->plugin) {
			$plugin = $this->plugin . '.';
		}

		$controllerClassName = $this->controllerName . 'Controller';
		App::uses($controllerClassName, $plugin . 'Controller');
		if (!class_exists($controllerClassName)) {
			$file = $controllerClassName . '.php';
			$this->err(__d('cake_console', "The file '%s' could not be found.\nIn order to bake a view, you'll need to first create the controller.", $file));
			return $this->_stop();
		}
		$controllerObj = new $controllerClassName();
		$controllerObj->plugin = $this->plugin;
		$controllerObj->constructClasses();
		$modelClass = $controllerObj->modelClass;
		$modelObj = $controllerObj->{$controllerObj->modelClass};

		if ($modelObj) {
			$primaryKey = $modelObj->primaryKey;
			$displayField = $modelObj->displayField;
			$singularVar = Inflector::variable($modelClass);
			$singularHumanName = $this->_singularHumanName($this->controllerName);
			$schema = $modelObj->schema(true);
			$fields = array_keys($schema);
			$associations = $this->_associations($modelObj);
		} else {
			$primaryKey = $displayField = null;
			$singularVar = Inflector::variable(Inflector::singularize($this->controllerName));
			$singularHumanName = $this->_singularHumanName($this->controllerName);
			$fields = $schema = $associations = array();
		}
		$pluralVar = Inflector::variable($this->controllerName);
		$pluralHumanName = $this->_pluralHumanName($this->controllerName);

		return compact('modelClass', 'schema', 'primaryKey', 'displayField', 'singularVar', 'pluralVar',
				'singularHumanName', 'pluralHumanName', 'fields', 'associations', 'modelObj');
	}

	/**
	 * Returns associations for controllers models.
	 *
	 * @param Model $model The Model instance.
	 * @return array associations
	 */
		protected function _associations(Model $model) {
			$keys = array('belongsTo', 'hasOne', 'hasMany', 'hasAndBelongsToMany');
			$associations = array();

			foreach ($keys as $type) {
				foreach ($model->{$type} as $assocKey => $assocData) {
					list(, $modelClass) = pluginSplit($assocData['className']);
					//$model->{$assocKey}->tablePrefix = '';
					$associations[$type][$assocKey]['primaryKey'] = $model->{$assocKey}->primaryKey;
					$associations[$type][$assocKey]['displayField'] = $model->{$assocKey}->displayField;
					$associations[$type][$assocKey]['foreignKey'] = $assocData['foreignKey'];
					$associations[$type][$assocKey]['controller'] = Inflector::pluralize(Inflector::underscore($modelClass));
					$associations[$type][$assocKey]['fields'] = array_keys($model->{$assocKey}->schema(true));
				}
			}
			return $associations;
		}
}