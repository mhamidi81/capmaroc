<?php
/**
 * CounselorsDocumentFixture
 *
 */
class CounselorsDocumentFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'filename' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'counselor_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'document_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'valid_rate' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'invalid_rate' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'updated' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'counselor_id' => array('column' => array('counselor_id', 'document_id'), 'unique' => 0),
			'document_id' => array('column' => 'document_id', 'unique' => 0)
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
			'filename' => 'Lorem ipsum dolor sit amet',
			'counselor_id' => 1,
			'document_id' => 1,
			'valid_rate' => 1,
			'invalid_rate' => 1,
			'created' => '2015-07-25 15:20:30',
			'updated' => '2015-07-25 15:20:30'
		),
	);

}
