<?php
/**
 * CounselorsLanguageFixture
 *
 */
class CounselorsLanguageFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'language_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'counselor_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'read' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'write' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'speak' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created_by' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false),
		'updated' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'updated_by' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'fk_languages_has_counselors_counselors1_idx' => array('column' => 'counselor_id', 'unique' => 0),
			'fk_languages_has_counselors_languages1_idx' => array('column' => 'language_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'language_id' => 1,
			'counselor_id' => 1,
			'read' => 1,
			'write' => 1,
			'speak' => 1,
			'created' => '2015-07-11 18:48:01',
			'created_by' => 1,
			'updated' => '2015-07-11 18:48:01',
			'updated_by' => 1
		),
	);

}
