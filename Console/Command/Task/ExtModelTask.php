<?php
/**
 * The ModelTask handles creating and updating models files.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 1.2
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('BakeTask', 'Console/Command/Task');

/**
 * Task class for creating and updating model files.
 *
 * @package	   Cake.Console.Command.Task
 */
class ExtModelTask extends ModelTask {

	public $name = "Model";

/**
 * Override initialize
 *
 * @return void
 */
	public function initialize() {
		$this->path = current(App::path('Model'));
	}

/**
 * Execution method always used for tasks
 *
 * @return void
 */
	public function execute() {
		
		if (isset($this->params['plugin'])) {
			$plugin = $this->params['plugin'];
			$pluginPath = $plugin . '.';
			App::uses($plugin.'AppModel', $pluginPath.'Model');
			$pluginmodel = $plugin.'AppModel';
			$plugin_model = new $pluginmodel();
			$db = ConnectionManager::getDataSource('default');
			$db->cacheSources = false;
			$config = $db->config;
			$config['prefix'] = $plugin_model->tablePrefix;
			ConnectionManager::create('tmpDataSource', $config);
			$this->connection = 'tmpDataSource';	
		}

		parent::execute();
	}

	public function _getDbConfigWithoutPrefix() {
		
			$db = ConnectionManager::getDataSource('default');
			$db->cacheSources = false;
			$config = $db->config;
			$config['prefix'] = '';
			ConnectionManager::create('emptyDataSource', $config);		

		return 'emptyDataSource';
	}

	public function _getTmpDbConfig($prefix) {
		
			$db = ConnectionManager::getDataSource('default');
			$db->cacheSources = false;
			$config = $db->config;
			$config['prefix'] = $prefix;
			ConnectionManager::create($prefix.'DataSource', $config);		

		return $prefix.'DataSource';
	}
	
	public function _getPrefixFromTable($table) {

			$tmp = explode('_', $table);

		return $tmp[0];
	}

/**
 * Get a model object for a class name.
 *
 * @param string $className Name of class you want model to be.
 * @param string $table Table name
 * @return Model Model instance
 */
	protected function _getModelObject($className, $table = null) {

		if (!$table) {
			$table = Inflector::tableize($className);
		}

		$connection = $this->connection;
		
		if($table)
		{
			
			$prefix = $this->_getPrefixFromTable($table);
			
			if(strlen($prefix) == 3)
			{
				$className = str_replace(Inflector::classify($prefix), '', $className);
				$table = str_replace($prefix.'_', '', $table);
				$connection = $this->_getTmpDbConfig($prefix.'_');
			}
			elseif($prefix == 'core'){
				$connection = $this->_getDbConfigWithoutPrefix();
			}

		}

		$object = new Model(array('name' => $className, 'table' => $table, 'ds' => $connection));
		$fields = $object->schema(true);
		foreach ($fields as $name => $field) {
			if (isset($field['key']) && $field['key'] === 'primary') {
				$object->primaryKey = $name;
				break;
			}
		}
		return $object;
	}

/**
 * Creates the proper model name from a foreign key
 *
 * @param string $key Foreign key
 * @return string Model name
 */
	/*protected function _modelNameFromKey($key) {
		$db = ConnectionManager::getDataSource($this->connection);
		$tmp = explode('_', $key);
		$key = str_replace($tmp[0].'_', '', $key);
		
		return parent::_modelNameFromKey($key);
	}*/

	protected function _pluginNameFromTable($key) {
		$db = ConnectionManager::getDataSource($this->connection);
		$swap = array(
				'rqm' => 'RequestManagment', 
				'pfm' => 'ProfileManagment', 
				'adm' => 'AddressesManagement',
				'cpm' => 'CompanyManagment',
				'cap' => 'Users',
				'msm' => 'MessageManagment'
		);

		$tmp = explode('_', $key);

		return ($tmp > 1 && strlen($tmp[0]) == 3)? $swap[$tmp[0]].'.' : '';
	}

	protected function _pluginNameFromKey($key) {
		$db = ConnectionManager::getDataSource($this->connection);
		$plugin_path = '';
		$swap = array(
			'Users' => array('user_id', 'role_id'),
			'RequestManagment' => array('request_id', 'status_id', 'meeting_id', 'judgment_id', 'administration_id'),
			'AddressesManagement' => array('country_id', 'city_id'),
			'ProfileManagment' => array('counselor_id', 'document_id'),
			'CompanyManagment' => array('company_id', 'document_id'),
			'MessageManagment' => array('sender_id', 'recipient_id'),
		);	

		foreach ($swap as $plugin => $keys) {
			
			if(in_array($key, $keys))
			{
				$plugin_path = $plugin.'.';

				break;
			}
		}

		return $plugin_path;
	}
/**
 * Find belongsTo relations and add them to the associations list.
 *
 * @param Model $model Model instance of model being generated.
 * @param array $associations Array of in progress associations
 * @return array Associations with belongsTo added in.
 */
	public function findBelongsTo(Model $model, $associations) {
		$fieldNames = array_keys($model->schema(true));
		foreach ($fieldNames as $fieldName) {
			$offset = substr($fieldName, -3) === '_id';
			if ($fieldName != $model->primaryKey && $fieldName !== 'parent_id' && $offset !== false) {
				$tmpModelName = $this->_modelNameFromKey($fieldName);
				$pluginPath	  = $this->_pluginNameFromKey($fieldName);

				$associations['belongsTo'][] = array(
					'alias' => $tmpModelName,
					'className' => $pluginPath.$tmpModelName,
					'foreignKey' => $fieldName,
				);
			} elseif ($fieldName === 'parent_id') {
				$associations['belongsTo'][] = array(
					'alias' => 'Parent' . $model->name,
					'className' => $pluginPath.$model->name,
					'foreignKey' => $fieldName,
				);
			}
		}
		return $associations;
	}

/**
 * Find the hasOne and hasMany relations and add them to associations list
 *
 * @param Model $model Model instance being generated
 * @param array $associations Array of in progress associations
 * @return array Associations with hasOne and hasMany added in.
 */
	public function findHasOneAndMany(Model $model, $associations) {
		$tmp = $this->_getDbConfigWithoutPrefix();
		$this->_tables = $this->getAllTables($tmp);
		$foreignKey = $this->_modelKey($model->name);
		$prefix = $this->_getPrefixFromTable($model->table);

		foreach ($this->_tables as $otherTable) {
			
			$tempOtherModel = $this->_getModelObject($this->_modelName($otherTable), $otherTable);

			$tempFieldNames = array_keys($tempOtherModel->schema(true));
			
			$pattern = '/_' . preg_quote($model->table, '/') . '|' . preg_quote($model->table, '/') . '_/';
			
			$possibleJoinTable = preg_match($pattern, $otherTable);
			
			if ($possibleJoinTable) {
				continue;
			}
			
			foreach ($tempFieldNames as $fieldName) {

				$assoc = false;
				if ($fieldName !== $model->primaryKey && $fieldName === $foreignKey) {

					$pluginPath	  = $this->_pluginNameFromTable($otherTable);
					$assoc = array(
						'alias' => $tempOtherModel->name,
						'className' => $pluginPath.$tempOtherModel->name,
						'foreignKey' => $fieldName
					);
				} elseif ($otherTable === $model->table && $fieldName === 'parent_id') {
					$assoc = array(
						'alias' => 'Child' . $model->name,
						'className' => $model->name,
						'foreignKey' => $fieldName
					);
				}
				if ($assoc) {
					$associations['hasOne'][] = $assoc;
					$associations['hasMany'][] = $assoc;
				}

			}
		}
		return $associations;
	}

/**
 * Find the hasAndBelongsToMany relations and add them to associations list
 *
 * @param Model $model Model instance being generated
 * @param array $associations Array of in-progress associations
 * @return array Associations with hasAndBelongsToMany added in.
 */
	public function findHasAndBelongsToMany(Model $model, $associations) {
		$foreignKey = $this->_modelKey($model->name);

		foreach ($this->_tables as $otherTable) {
			$tableName = null;
			$offset = strpos($otherTable, $model->table . '_');
			$otherOffset = strpos($otherTable, '_' . $model->table);

			if ($offset !== false) {
				$tableName = substr($otherTable, strlen($model->table . '_'));
			} elseif ($otherOffset !== false) {
				$tableName = substr($otherTable, 0, $otherOffset);
			}
			
			if ($tableName && in_array($tableName, $this->_tables)) {
				$habtmName = $this->_modelName($tableName);
				$associations['hasAndBelongsToMany'][] = array(
					'alias' => $habtmName,
					'className' => $habtmName,
					'foreignKey' => $foreignKey,
					'associationForeignKey' => $this->_modelKey($habtmName),
					'joinTable' => $otherTable
				);
			}
		}
		return $associations;
	}



/**
 * outputs the a list of possible models or controllers from database
 *
 * @param string $useDbConfig Database configuration name
 * @return array
 */
	public function listAll($useDbConfig = null) {

		$this->_tables = $this->getAllTables($useDbConfig);

		$this->_modelNames = array();
		$count = count($this->_tables);
		for ($i = 0; $i < $count; $i++) {
			$this->_modelNames[] = $this->_modelName($this->_tables[$i]);
		}
		if ($this->interactive === true) {
			$this->out(__d('cake_console', 'Possible Models based on your current database:'));
			$len = strlen($count + 1);
			for ($i = 0; $i < $count; $i++) {
				$this->out(sprintf("%${len}d. %s", $i + 1, $this->_modelNames[$i]));
			}
		}
		return $this->_tables;
	}

/**
 * Interact with the user to determine the table name of a particular model
 *
 * @param string $modelName Name of the model you want a table for.
 * @param string $useDbConfig Name of the database config you want to get tables from.
 * @return string Table name
 */
	public function getTable($modelName, $useDbConfig = null) {
		$useTable = Inflector::tableize($modelName);
		if (in_array($modelName, $this->_modelNames)) {
			$modelNames = array_flip($this->_modelNames);
			$useTable = $this->_tables[$modelNames[$modelName]];
		}

		if ($this->interactive === true) {
			if (!isset($useDbConfig)) {
				$useDbConfig = $this->connection;
			}
			$db = ConnectionManager::getDataSource($useDbConfig);
			$fullTableName = $db->fullTableName($useTable, false);
			$tableIsGood = false;
			if (array_search($useTable, $this->_tables) === false) {
				$this->out();
				$this->out(__d('cake_console', "Given your model named '%s',\nCake would expect a database table named '%s'", $modelName, $fullTableName));
				$tableIsGood = $this->in(__d('cake_console', 'Do you want to use this table?'), array('y', 'n'), 'y');
			}
			if (strtolower($tableIsGood) === 'n') {
				$useTable = $this->in(__d('cake_console', 'What is the name of the table?'));
			}
		}
		return $useTable;
	}

/**
 * Assembles and writes a Model file.
 *
 * @param string|object $name Model name or object
 * @param array|bool $data if array and $name is not an object assume bake data, otherwise boolean.
 * @return string
 */
	public function bake($name, $data = array()) {
		if ($name instanceof Model) {
			if (!$data) {
				$data = array();
				$data['associations'] = $this->doAssociations($name);
				$data['validate'] = $this->doValidation($name);
				$data['actsAs'] = array('Containable');
			}
			$data['primaryKey'] = $name->primaryKey;
			$data['useTable'] = $name->table;
			$data['useDbConfig'] = 'default';
			$data['name'] = $name = $name->name;
		} else {
			$data['name'] = $name;
		}

		$defaults = array(
			'associations' => array(),
			'actsAs' => array('Containable'),
			'validate' => array(),
			'primaryKey' => 'id',
			'useTable' => null,
			'useDbConfig' => 'default',
			'displayField' => null
		);
		$data = array_merge($defaults, $data);

		$pluginPath = '';
		if ($this->plugin) {
			$pluginPath = $this->plugin . '.';
		}

		$this->Template->set($data);
		$this->Template->set(array(
			'plugin' => $this->plugin,
			'pluginPath' => $pluginPath
		));
		$out = $this->Template->generate('classes', 'model');

		$path = $this->getPath();
		$filename = $path . $name . '.php';
		$this->out("\n" . __d('cake_console', 'Baking model class for %s...', $name), 1, Shell::QUIET);
		$this->createFile($filename, $out);
		ClassRegistry::flush();
		return $out;
	}

}
