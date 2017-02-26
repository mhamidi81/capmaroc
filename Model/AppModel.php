<?php

App::uses('CroogoAppModel', 'Croogo.Model');

/**
 * Base Application model
 *
 * @package  Croogo
 * @link     http://www.croogo.org
 */
class AppModel extends CroogoAppModel {
	public $actsAs = array('Containable');

	/**
 * Loads and instantiates models.
 * If the model is non existent, it will throw a missing database table error, as Cake generates
 * dynamic models for the time being.
 *
 * Will clear the model's internal state using Model::create()
 *
 * @param string $modelName Name of model class to load
 * @param mixed $options array|string
 *              id      Initial ID the instanced model class should have
 *              alias   Variable alias to write the model to
 * @return mixed true when single model found and instance created, error returned if model not found.
 * @access public
 */

	function loadModel($modelName, $options = array()) {
        if (is_string($options)) $options = array('alias' => $options);
        $options = array_merge(array(
            'datasource'  => 'default',
            'alias'       => false,
            'id'          => false,
        ), $options);
        list($plugin, $className) = pluginSplit($modelName, true, null);
        if (empty($options['alias'])) $options['alias'] = $className;
        if (!isset($this->{$options['alias']}) || $this->{$options['alias']}->name !== $className) {
            if (!class_exists($className)) {
                if ($plugin) $plugin = "{$plugin}";
                App::import('Model', "{$plugin}{$className}");
            }
            //die("{$plugin}{$className}");
            $table = Inflector::tableize($className);

            $this->{$options['alias']} = new $className($options['id'], $table, $options['datasource']);
            if (!$this->{$options['alias']}) {
                return $this->cakeError('missingModel', array(array(
                    'className' => $className, 'code' => 500
                )));
            }
            $this->{$options['alias']}->alias = $options['alias'];
        }
        $this->{$options['alias']}->create();
        return true;
    }

}
