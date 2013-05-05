<?php
App::uses('SocialAccount', 'Model');

/**
 * SocialAccount Test Case
 *
 */
class SocialAccountTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.social_account',
		'app.user'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SocialAccount = ClassRegistry::init('SocialAccount');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SocialAccount);

		parent::tearDown();
	}

}
