<?php
/**
 * UserFixture
 *
 */
class UserFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'mail' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'key' => 'unique', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'create_date' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'last_update' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'last_access' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'ip' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'reauth_token' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'mail_UNIQUE' => array('column' => 'mail', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'mail' => 'Lorem ipsum dolor sit amet',
			'create_date' => '2013-05-05 17:52:39',
			'last_update' => '2013-05-05 17:52:39',
			'last_access' => '2013-05-05 17:52:39',
			'ip' => 'Lorem ipsum d',
			'reauth_token' => 'Lorem ipsum dolor sit amet'
		),
	);

}
